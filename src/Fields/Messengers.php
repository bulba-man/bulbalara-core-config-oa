<?php

namespace Bulbalara\CoreConfigOa\Fields;

use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\NestedForm;

class Messengers extends AbstractConfigField
{
    protected function init(): ?Field
    {
        return $this->makeFormField('table', $this->getFieldName(), $this->getLabel(), function (NestedForm $form) {
            $form->icon('icon', __('bl.config::core_config.config.contacts.general.messengers_icon'));
            $form->text('name', __('bl.config::core_config.config.contacts.general.messengers_name'));
            $form->text('value', __('bl.config::core_config.config.contacts.general.messengers_value'));
        })->sortable()->value($this->getValue() ?: []);
    }
}
