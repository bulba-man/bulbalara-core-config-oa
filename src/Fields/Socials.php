<?php

namespace Bulbalara\CoreConfigOa\Fields;

use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\NestedForm;

class Socials extends AbstractConfigField
{
    protected function init(): ?Field
    {
        return $this->makeFormField('table', $this->getFieldName(), $this->getLabel(), function (NestedForm $form) {
            $form->icon('icon', __('bl.config::core_config.config.contacts.general.socials_icon'));
            $form->url('value', __('bl.config::core_config.config.contacts.general.socials_url'));
        })->sortable()->value($this->getValue() ?: []);
    }
}
