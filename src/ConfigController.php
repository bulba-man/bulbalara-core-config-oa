<?php

namespace Bulbalara\CoreConfigOa;

use Bulbalara\CoreConfigOa\Fields\AbstractConfigField;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form as BaseForm;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Widgets\Box;
use OpenAdmin\Admin\Widgets\Form;
use Symfony\Component\HttpFoundation\Response;

class ConfigController extends AdminController
{

    public function index(Content $content)
    {
        $this->prepareSession();

        return $content
            ->title(__('bl.config::core_config.menu_config_label'))
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $allFields = $this->makeFields();
                    $tree = $this->makeTree();

                    $tab = new \OpenAdmin\Admin\Widgets\Tab();
                    foreach ($tree as $section => $groups) {
                        $form = $this->makeForm($section, $groups, $allFields);

                        $tabLabel = __(\ConfigOA::tabLabel($section)) ?: ucfirst($section);
                        $tab->add($tabLabel, $form->render(), false, $section);
                    }

                    $column->append((new Box(' ', $tab))->style('success'));
                });
            });
    }

    protected function makeForm(string $section, array $groups, array $allFields): Form
    {
        $form = new Form();
        $actionUri = config('bl.config.urls.settings_uri') . '/' . config('bl.config.urls.settings_config_uri');
        $form->action(admin_url($actionUri));
        $form->hidden('_token')->default(csrf_token());

        $groupCounter = 0;
        foreach ($groups as $group => $groupFields) {
            $groupLabel = __(\ConfigOA::tabGroupLabel($section.'.'.$group)) ?: ucfirst($group);
            $form->fieldset($groupLabel, function (Form $form) use($groupFields, $allFields) {

                foreach ($groupFields as $fieldName => $groupField) {
                    if (!isset($allFields[$groupField['path']])) {
                        continue;
                    }

                    $field = $allFields[$groupField['path']];
                    $form->pushField($field);

                    if (isset($groupField['dependencies']) && count($groupField['dependencies'])) {
                        foreach ($groupField['dependencies'] as $value => $depFields) {
                            $field->when($value, function (Form $form) use ($depFields, $allFields){
                                foreach ($depFields as $depFieldName) {
                                    if (!isset($allFields[$depFieldName])) {
                                        continue;
                                    }

                                    $form->pushField($allFields[$depFieldName]);
                                }
                            });
                        }
                    }
                }

            }, (bool) $groupCounter);

            $groupCounter++;
        }

        return $form;
    }

    protected function makeTree()
    {
        $configs = ConfigModel::with('coreConfig:path,id')->get(['config_id', 'depends_of', 'depends_val', 'order']);

        $configsTree = [];
        foreach ($configs as $configItem) {
            $fieldPath = $configItem->coreConfig->path;

            if ($configItem->depends_of) {
                [$depSection, $depGroup, $depField] = explode('.', $configItem->depends_of);
                $configsTree[$depSection][$depGroup][$depField]['dependencies'][$configItem->depends_val][] = $fieldPath;
            } else {
                [$section, $group, $field] = explode('.', $fieldPath);
                $fieldData = [
                    'path' => $configItem->coreConfig->path,
                    'order' => $configItem->order,
                ];

                if (isset($configsTree[$section][$group][$field])) {
                    $configsTree[$section][$group][$field] = array_merge(
                        $configsTree[$section][$group][$field],
                        $fieldData
                    );
                } else {
                    $configsTree[$section][$group][$field] = $fieldData;
                }
            }
        }

        foreach ($configsTree as $section => $groups) {
            foreach ($groups as $group => $configs) {
                $configsTree[$section][$group] = collect($configs)->sortBy('order')->toArray();
            }
        }

        return $configsTree;
    }

    protected function makeFields(): array
    {
        $configs = ConfigModel::with('coreConfig')->get();

        /** @var Field[] $formFields */
        $formFields = [];
        foreach ($configs as $configItem) {
            $field = $this->makeField($configItem);
            if ($field) {
                $formFields[$configItem->coreConfig->path] = $this->fillField($field, $configItem);;
            }
        }

        return $formFields;
    }

    protected function makeField(ConfigModel $configItem): ?Field
    {
        $fieldName = $configItem->coreConfig->path;
        $fieldLabel = __($configItem->label);

        /** @var Field $field */
        if ($configItem->backend_type
            && class_exists($configItem->backend_type)
            && is_subclass_of($configItem->backend_type, AbstractConfigField::class)
        ) {
            $configField = new $configItem->backend_type($configItem);
            $field = $configField->getField();
        } else if (isset(BaseForm::$availableFields[$configItem->backend_type])) {
            $class = BaseForm::$availableFields[$configItem->backend_type];
            $field = new $class($fieldName, [$fieldLabel]);
        }

        return $field ?? null;
    }

    protected function fillField(Field $field, ConfigModel $configItem): Field
    {
        if (!$field->value()) {
            $field->setValue($configItem->coreConfig->value);
        }

        $field->setOriginal([$field->column() => $configItem->coreConfig->value]);

        if ($configItem->description) {
            $field->help(__($configItem->description));
        }

        if ($configItem->source) {
            $field->options($configItem->source);
        }

        if (method_exists($field,'useNative')) {
            $field->useNative();
        }

        $field->defaultOnNull($configItem->coreConfig->default);
//        $field->default($configItem->coreConfig->default);
        if ($configItem->resettable !== false) {
            $field->resettable();
        }

        if ($configItem->rules) {
            $field->rules($configItem->rules);
        }

        return $field;
    }

    protected function prepareSession(): void
    {
        $old_input = \request()->session()->get('_old_input', []);

        foreach ($old_input as $section => $groups) {
            if (!is_array($groups)) {
                continue;
            }
            foreach ($groups as $groupName => $groupFields) {
                if (!is_array($groupFields)) {
                    continue;
                }
                foreach ($groupFields as $fieldName => $value) {
                    if (is_array($value) && isset($value['inherit']) && $value['inherit']) {
                        unset($old_input[$section][$groupName][$fieldName]);
                        continue;
                    }

                    if (is_array($value) && isset($value['value'])) {
                        $old_input[$section][$groupName][$fieldName] = $value['value'];
                    }
                }
            }
        }

        \request()->session()->put('_old_input', $old_input);
    }

    public function update($id)
    {

    }

    public function store()
    {
        $data = \request()->all();

        $allFields = $this->makeFields();
        $tree = $this->makeTree();

        $activeSection = '';

         foreach ($data as $section => $groups) {
             if (!isset($tree[$section])) {
                 continue;
             }

             $activeSection = $section;

             $form = $this->makeForm($section, $tree[$section], $allFields);

             $fields = [];
             foreach ($groups as $groupName => $groupFields) {
                 foreach ($groupFields as $fieldName => $value) {
                     $path = $section.'.'.$groupName.'.'.$fieldName;

                     if (is_array($value) && array_key_exists('inherit', $value) && $value['inherit']) {
                         if ($allFields[$path]->getDefaultOnNull() == $allFields[$path]->value()) {
                             continue;
                         }

                         $fields[$section][$groupName][$fieldName] = null;
//                         $data[$section][$groupName][$fieldName] = ['value' => null];
//                         $fields[$path] = null;
                         continue;
                     }

                     if (is_array($value) && array_key_exists('value', $value)) {
                         $fields[$section][$groupName][$fieldName] = (is_null($value['value'])) ? '' : $value['value'];
//                         $data[$section][$groupName][$fieldName] = ['value' => $value['value']];
//                         $fields[$path] = $value['value'];
                         continue;
                     }

                     if (is_array($value) && array_key_exists('value'.Field::FILE_DELETE_FLAG, $value)) {
                         $fields[$section][$groupName][$fieldName.Field::FILE_DELETE_FLAG] = $value['value'.Field::FILE_DELETE_FLAG];
                         continue;
                     }



                     $fields[$section][$groupName][$fieldName] = $value;
//                     $fields[$path] = $value;
                 }
             }

             \request()->merge([
                 $section => $fields[$section],
             ]);

             if ($validationMessages = $form->validateData($fields)) {
                 return $this->responseValidationError($validationMessages, $section);
             }



             DB::transaction(function () use ($fields, $form) {
                 $updates = $this->prepareUpdate($fields, $form);

                 $collection = \Bulbalara\CoreConfig\Models\Config::whereIn('path', array_keys($updates))->get();
                 foreach ($collection as $config) {
                     $config->setAttribute('value', $updates[$config->path]);
                     $config->save();
                 }
             });
         }


        if ($response = $this->ajaxResponse(trans('admin.update_succeeded'))) {
            return $response;
        }

        $actionUri = config('bl.config.urls.settings_uri') . '/' . config('bl.config.urls.settings_config_uri');
        return redirect(admin_url($actionUri).'#'.$activeSection)->withFragment($activeSection);
    }

    protected function prepareUpdate(array $updates, Form $form): array
    {
        $prepared = [];

        /** @var Field $field */
        foreach ($form->fields() as $field) {
            $columns = $field->column();


            $value = $this->getDataByColumn($updates, $columns);
            $value = $field->prepare($value);

            // only process values if not false
            if ($value !== false) {
                if (is_array($columns)) {
                    foreach ($columns as $name => $column) {
                        $prepared[$column] = $value[$name];
                    }
                } elseif (is_string($columns)) {
                    $prepared[$columns] = $value;
                }
            }
        }

        return $prepared;
    }

    protected function getDataByColumn($data, $columns)
    {
        if (is_string($columns)) {
            return Arr::get($data, $columns, false);
        }

        if (is_array($columns)) {
            $value = [];
            foreach ($columns as $name => $column) {
                if (!Arr::has($data, $column)) {
                    continue;
                }
                $value[$name] = Arr::get($data, $column, false);
            }

            return $value;
        }
        // if not found return false
        // false values won't be save
        return false;
    }
    protected function responseValidationError(MessageBag $message, string $tab = '')
    {
        if (\request()->ajax() && !\request()->pjax()) {
            return response()->json([
                'status'     => false,
                'validation' => $message,
                'message'    => $message->first(),
            ]);
        }

        return back()->withInput()->withErrors($message);
    }

    protected function ajaxResponse($message)
    {
        $request = \request();

        // ajax but not pjax
        if ($request->ajax() && !$request->pjax()) {
            return response()->json([
                'status'  => true,
                'message' => $message,
//                'display' => $this->applayFieldDisplay(),
            ]);
        }

        return false;
    }
}
