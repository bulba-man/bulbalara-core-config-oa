<?php

namespace Bulbalara\CoreConfigOa\Database;

use Bulbalara\CatalogOa\Models\Source\Config\ListModes;
use Bulbalara\CatalogOa\Models\Source\Config\ListSort;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \Bulbalara\CoreConfig\Models\Config::truncate();
        \Bulbalara\CoreConfigOa\ConfigModel::truncate();
        Schema::enableForeignKeyConstraints();

        \ConfigOA::addConfig('general.general.site_name', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.general.general.site_name'
        ]);

        \ConfigOA::addConfig('mail.transport.mailer', [
            'value' => 'smtp',
            'cast' => 'string',
            'default' => 'log',
            'backend_type' => 'select',
            'source' => json_encode(['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'log' => 'Log']),
            'label' => 'bl.config::core_config.config.mail.transport.mailer'
        ]);

        \ConfigOA::addConfig('mail.transport.smtp.host', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'depends_of' => 'mail.transport.mailer',
            'depends_val' => 'smtp',
            'label' => 'bl.config::core_config.config.mail.transport.smtp.host'
        ]);

        \ConfigOA::addConfig('mail.transport.smtp.port', [
            'value' => '465',
            'cast' => 'string',
            'default' => '465',
            'backend_type' => 'text',
            'depends_of' => 'mail.transport.mailer',
            'depends_val' => 'smtp',
            'label' => 'bl.config::core_config.config.mail.transport.smtp.port',
            'description' => 'bl.config::core_config.config.mail.transport.smtp.port-help'
        ]);
        \ConfigOA::addConfig('mail.transport.smtp.encryption', [
            'value' => 'tls',
            'cast' => 'string',
            'default' => 'tls',
            'backend_type' => 'select',
            'source' => json_encode(['ssl' => 'SSL', 'tls' => 'TLS']),
            'depends_of' => 'mail.transport.mailer',
            'depends_val' => 'smtp',
            'label' => 'bl.config::core_config.config.mail.transport.smtp.encryption'
        ]);
        \ConfigOA::addConfig('mail.transport.smtp.username', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'depends_of' => 'mail.transport.mailer',
            'depends_val' => 'smtp',
            'label' => 'bl.config::core_config.config.mail.transport.smtp.username'
        ]);
        \ConfigOA::addConfig('mail.transport.smtp.password', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'depends_of' => 'mail.transport.mailer',
            'depends_val' => 'smtp',
            'label' => 'bl.config::core_config.config.mail.transport.smtp.password'
        ]);

        \ConfigOA::addConfig('mail.transport.log.channel', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'depends_of' => 'mail.transport.mailer',
            'depends_val' => 'log',
            'label' => 'bl.config::core_config.config.mail.transport.log.channel'
        ]);


        \ConfigOA::addConfig('mail.addresses.from.address', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'email',
            'label' => 'bl.config::core_config.config.mail.addresses.sender_email'
        ]);

        \ConfigOA::addConfig('mail.addresses.from.name', [
            'value' => '',
            'cast' => 'string',
            'default' => 'Sender',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.mail.addresses.sender_name'
        ]);

        \ConfigOA::addConfig('mail.addresses.receivers', [
            'value' => '',
            'cast' => 'json',
            'default' => '[]',
            'backend_type' => 'list',
            'label' => 'bl.config::core_config.config.mail.addresses.receivers',
            'rules' => 'nullable|email'
        ]);

        /*
                \ConfigOA::addConfig('catalog.fields_masks.sku', [
                    'value' => '{{name}}',
                    'cast' => 'string',
                    'default' => '{{name}}',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.fields_masks.sku',
                    'description' => '',
                ]);


                \ConfigOA::addConfig('catalog.fields_masks.meta_title', [
                    'value' => '{{name}}',
                    'cast' => 'string',
                    'default' => '{{name}}',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.fields_masks.meta_title',
                    'description' => '',
                ]);


                \ConfigOA::addConfig('catalog.fields_masks.meta_keyword', [
                    'value' => '{{name}}',
                    'cast' => 'string',
                    'default' => '{{name}}',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.fields_masks.meta_keyword',
                    'description' => '',
                ]);


                \ConfigOA::addConfig('catalog.fields_masks.meta_description', [
                    'value' => '{{name}} {{description}}',
                    'cast' => 'string',
                    'default' => '{{name}} {{description}}',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.fields_masks.meta_description',
                    'description' => '',
                ]);


                \ConfigOA::addConfig('catalog.frontend.show_price', [
                    'value' => 1,
                    'cast' => 'boolean',
                    'default' => 1,
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.frontend.show_price',
                    'description' => '',
                ]);

                \ConfigOA::addConfig('catalog.frontend.list_mode', [
                    'value' => ListModes::GRID_LIST,
                    'cast' => 'string',
                    'default' => ListModes::GRID_LIST,
                    'backend_type' => 'select',
                    'source' => ListModes::class,
                    'label' => 'bl.catalog::admin.config.catalog.frontend.list_mode',
                    'description' => '',
                ]);

                \ConfigOA::addConfig('catalog.frontend.per_page_list', [
                    'value' => '12,24,36',
                    'cast' => 'string',
                    'default' => '12,24,36',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.frontend.per_page_list',
                    'description' => 'bl.catalog::admin.config.catalog.frontend.per_page_list-help',
                ]);

                \ConfigOA::addConfig('catalog.frontend.per_page_default', [
                    'value' => '12',
                    'cast' => 'string',
                    'default' => '12',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.frontend.per_page_default',
                    'description' => 'bl.catalog::admin.config.catalog.frontend.per_page_default-help',
                ]);

                \ConfigOA::addConfig('catalog.frontend.default_sort_by', [
                    'value' => 'position',
                    'cast' => 'string',
                    'default' => 'position',
                    'backend_type' => 'select',
                    'source' => ListSort::class,
                    'label' => 'bl.catalog::admin.config.catalog.frontend.default_sort_by',
                    'description' => 'bl.catalog::admin.config.catalog.frontend.default_sort_by-help',
                ]);

                \ConfigOA::addConfig('catalog.frontend.list_allow_all', [
                    'value' => '0',
                    'cast' => 'boolean',
                    'default' => '0',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.frontend.list_allow_all',
                    'description' => 'bl.catalog::admin.config.catalog.frontend.list_allow_all-help',
                ]);

                \ConfigOA::addConfig('catalog.frontend.review', [
                    'value' => '0',
                    'cast' => 'boolean',
                    'default' => '0',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.frontend.review',
                    'description' => '',
                ]);




                \ConfigOA::addConfig('catalog.seo.product_url_suffix_enable', [
                    'value' => '1',
                    'cast' => 'boolean',
                    'default' => '1',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.seo.product_url_suffix_enable'
                ]);
                \ConfigOA::addConfig('catalog.seo.product_url_suffix', [
                    'value' => '.html',
                    'cast' => 'string',
                    'default' => '.html',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.seo.product_url_suffix',
                    'description' => '',
                ]);
                \ConfigOA::addConfig('catalog.seo.category_url_suffix_enable', [
                    'value' => '1',
                    'cast' => 'boolean',
                    'default' => '1',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.seo.category_url_suffix_enable'
                ]);
                \ConfigOA::addConfig('catalog.seo.category_url_suffix', [
                    'value' => '.html',
                    'cast' => 'string',
                    'default' => '.html',
                    'backend_type' => 'text',
                    'label' => 'bl.catalog::admin.config.catalog.seo.category_url_suffix',
                    'description' => '',
                ]);

                \ConfigOA::addConfig('catalog.seo.use_catalog', [
                    'value' => '1',
                    'cast' => 'boolean',
                    'default' => '1',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.seo.use_catalog'
                ]);
                \ConfigOA::addConfig('catalog.seo.product_use_categories', [
                    'value' => '1',
                    'cast' => 'boolean',
                    'default' => '1',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.seo.product_use_categories'
                ]);

                \ConfigOA::addConfig('catalog.seo.category_canonical_tag', [
                    'value' => '1',
                    'cast' => 'boolean',
                    'default' => '1',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.seo.category_canonical_tag'
                ]);
                \ConfigOA::addConfig('catalog.seo.product_canonical_tag', [
                    'value' => '1',
                    'cast' => 'boolean',
                    'default' => '1',
                    'backend_type' => 'switch',
                    'label' => 'bl.catalog::admin.config.catalog.seo.product_canonical_tag'
                ]);

                \ConfigOA::addConfig('catalog.currency.base', [
                    'value' => 'USD',
                    'cast' => 'string',
                    'default' => 'USD',
                    'backend_type' => 'select',
                    'source' => \Bulbalara\CatalogOa\Models\Source\Config\Currencies::class,
                    'label' => 'bl.catalog::admin.config.catalog.currency.base',
                    'description' => 'bl.catalog::admin.config.catalog.currency.base-help',
                ]);

                \ConfigOA::addConfig('catalog.currency.default', [
                    'value' => 'USD',
                    'cast' => 'string',
                    'default' => 'USD',
                    'backend_type' => 'select',
                    'source' => \Bulbalara\CatalogOa\Models\Source\Config\Currencies::class,
                    'label' => 'bl.catalog::admin.config.catalog.currency.default',
                ]);

                \ConfigOA::addConfig('catalog.currency.allow', [
                    'value' => '',
                    'cast' => 'json',
                    'default' => '["USD", "BYN"]',
                    'backend_type' => 'listbox',
                    'source' => \Bulbalara\CatalogOa\Models\Source\Config\Currencies::class,
                    'label' => 'bl.catalog::admin.config.catalog.currency.allow',
                ]);
        */

        \ConfigOA::addConfig('design.head.favicon', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'image',
            'label' => 'bl.config::core_config.config.design.head.favicon',
        ]);
        \ConfigOA::addConfig('design.head.html_lang', [
            'value' => 'ru',
            'cast' => 'string',
            'default' => 'en',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.design.head.html_lang',
            'description' => 'bl.config::core_config.config.design.head.html_lang-help',
        ]);
        \ConfigOA::addConfig('design.head.custom_scripts', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'textarea',
            'label' => 'bl.config::core_config.config.design.head.custom_scripts',
        ]);
        \ConfigOA::addConfig('design.head.custom_styles', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'textarea',
            'label' => 'bl.config::core_config.config.design.head.custom_styles',
        ]);


        \ConfigOA::addConfig('design.header.logo', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'image',
            'label' => 'bl.config::core_config.config.design.header.logo',
        ]);
        \ConfigOA::addConfig('design.header.logo_width', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.design.header.logo_width',
            'description' => 'px',
        ]);
        \ConfigOA::addConfig('design.header.logo_height', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.design.header.logo_height',
            'description' => 'px',
        ]);
        \ConfigOA::addConfig('design.header.logo_alt', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.design.header.logo_alt',
        ]);
        \ConfigOA::addConfig('design.footer.copyright', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.design.footer.copyright',
        ]);

        \ConfigOA::addConfig('seo.defaults.default_title', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.seo.defaults.default_title',
        ]);
        \ConfigOA::addConfig('seo.defaults.title_prefix', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.seo.defaults.title_prefix',
        ]);
        \ConfigOA::addConfig('seo.defaults.title_suffix', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.seo.defaults.title_suffix',
        ]);
        \ConfigOA::addConfig('seo.defaults.default_description', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'textarea',
            'label' => 'bl.config::core_config.config.seo.defaults.default_description',
        ]);
        \ConfigOA::addConfig('seo.defaults.default_keywords', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'textarea',
            'label' => 'bl.config::core_config.config.seo.defaults.default_keywords',
        ]);




        \ConfigOA::addConfig('contacts.general.phone', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.contacts.general.phone',
        ]);
        \ConfigOA::addConfig('contacts.general.email', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.contacts.general.email',
        ]);
        \ConfigOA::addConfig('contacts.general.address', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.contacts.general.address',
        ]);
        \ConfigOA::addConfig('contacts.general.schedule', [
            'value' => '',
            'cast' => 'string',
            'default' => '',
            'backend_type' => 'text',
            'label' => 'bl.config::core_config.config.contacts.general.schedule',
        ]);
        \ConfigOA::addConfig('contacts.general.messengers', [
            'value' => '',
            'cast' => 'json',
            'default' => '',
            'backend_type' => \Bulbalara\CoreConfigOa\Fields\Messengers::class,
            'label' => 'bl.config::core_config.config.contacts.general.messengers',
        ]);
        \ConfigOA::addConfig('contacts.general.socials', [
            'value' => '',
            'cast' => 'json',
            'default' => '',
            'backend_type' => \Bulbalara\CoreConfigOa\Fields\Socials::class,
            'label' => 'bl.config::core_config.config.contacts.general.socials',
        ]);






    }


}
