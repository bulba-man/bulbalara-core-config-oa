<?php

namespace Bulbalara\CoreConfigOa\Fields;

use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Widgets\Form;

class MailTransport extends AbstractConfigField
{
    protected function init(): ?Field
    {
        $val = $this->getValue();
        $fieldName = $this->getFieldName();

        $field = $this->makeFormField('select', $fieldName, $this->getLabel());

        $field
            ->useNative()
            ->options(['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'log' => 'Log'])
            ->when('smtp', function (Form $form) use ($val, $fieldName) {

                $form->embeds($fieldName.'_mailers_smtp', '', function ($form) use ($fieldName) {
                    $form->text('host', 'Host')
                        ->rules('required');
                    $form->text('port', 'Port')
                        ->default(465)
                        ->help('Стандартные: 25, 465 (для SSL) и 587 (для TLS)')
                        ->rules('required');
                    $form->select('encryption', 'Encryption')
                        ->useNative()
                        ->options(['ssl' => 'SSL', 'tls' => 'TLS'])
                        ->default('tls')
                        ->rules('required')
                        ->removeElementClass($fieldName.'[mailers][smtp]_encryption')
                        ->setElementClass($fieldName.'_mailers_smtp_encryption');
                    $form->text('username', 'Username')->rules('required');
                    $form->text('password', 'Password')->rules('required');
                })->setElementName('transport_config[mailers][smtp]')
                    ->value($val['mailers']['smtp'] ?? []);
            })
            ->when('log', function (Form $form) use ($fieldName, $val){
                $form->embeds($fieldName.'__mailers__log', '', function ($form) {
                    $form->text('channel', 'Channel');
                })->setElementName($fieldName.'[mailers][log]')
                    ->value($val['mailers']['log'] ?? []);
            })
            ->default('log')
            ->setElementName($fieldName.'[mailer]')
            ->value($val['mailer'] ?? null);

        return $field;
    }
}
