<?php

namespace Bulbalara\CoreConfigOa\Fields;

use Bulbalara\CoreConfigOa\ConfigModel;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Widgets\Form;
use OpenAdmin\Admin\Form as BaseForm;

abstract class AbstractConfigField
{
    protected ConfigModel $config;

    protected string $fieldType;

    protected ?Field $field;

    abstract protected function init():?Field;

    public function __construct(ConfigModel $config)
    {
        $this->config = $config;

        $this->field = $this->init();
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    protected function makeFormField (string $name, ...$arguments): ?Field
    {
        if (!isset(BaseForm::$availableFields[$name])) {
            return null;
        }

        $class = BaseForm::$availableFields[$name];
        $field = new $class(\Arr::get($arguments, 0), array_slice($arguments, 1));

        return $field;
    }

    protected function getValue()
    {
        return $this->config->coreConfig->value;
    }

    protected function getDefault()
    {
        return $this->config->coreConfig->default;
    }

    protected function getFieldName(): string
    {
        return $this->config->coreConfig->path;
    }

    protected function getLabel(): string
    {
        return __($this->config->label);
    }

    protected function getHelp(): string
    {
        return __($this->config->description);
    }
}
