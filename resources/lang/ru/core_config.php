<?php

return [
    'menu_main_label' => 'Настройки',
    'menu_config_label' => 'Конфигурация',

    'role_main_label' => 'Настройки',
    'role_config_label' => 'Настройки: Конфигурация',

    'config' => [
        'general' => [
            'tab_label' => 'Сайт',
            'general' => [
                'group_label' => 'Основные настройки',
                'site_name' => 'Название сайта'
            ],
        ],
        'web' => [
            'tab_label' => 'Веб',
            'assets' => [
                'group_label' => 'Ресурсы',
                'favicon' => 'Favicon',
                'logo' => 'Логотип',
                'html_lang' => 'Атрибут "lang" тега &lt;html&gt;',
                'custom_scripts' => 'Javascript',
                'custom_styles' => 'CSS Стили',
            ],
        ],
        'design' => [
            'tab_label' => 'Дизайн',
            'head' => [
                'group_label' => 'Head сайта',
                'favicon' => 'Favicon',
                'html_lang' => '&lt;html&gt; lang',
                'html_lang-help' => 'Атрибут "lang" тега &lt;html&gt;',
                'custom_scripts' => 'Javascript',
                'custom_styles' => 'CSS Стили',
            ],
            'header' => [
                'group_label' => 'Header сайта',
                'logo' => 'Логотип',
                'logo_width' => 'Ширина логотипа',
                'logo_height' => 'Высота логотипа',
                'logo_alt' => 'Alt атрибут логотипа',
            ],
            'footer' => [
                'group_label' => 'Footer сайта',
                'copyright' => 'Копирайт',
            ],
        ],
        'seo' => [
            'tab_label' => 'SEO',
            'defaults' => [
                'group_label' => 'Настройки по умолчанию',
                'default_title' => 'Meta title',
                'title_prefix' => 'Префикс для Meta title',
                'title_suffix' => 'Суффикс для Meta title',
                'default_description' => 'Meta Description',
                'default_keywords' => 'Meta Keywords',

            ],
        ],
        'contacts' => [
            'tab_label' => 'Контакты',
            'general' => [
                'group_label' => 'Основное',
                'phone' => 'Телефон',
                'email' => 'Email',
                'address' => 'Адрес',
                'schedule' => 'Время работы',
                'messengers' => 'Мессенджеры',
                'messengers_icon' => 'Иконка',
                'messengers_name' => 'Название',
                'messengers_value' => 'Значение',
                'socials' => 'Социальные сети',
                'socials_icon' => 'Иконка',
                'socials_url' => 'Адрес',

            ],
        ],
        'mail' => [
            'tab_label' => 'Почта',
            'transport' => [
                'group_label' => 'Настройки Транспорта',
                'config' => 'Использовать для отправки'
            ],
            'addresses' => [
                'group_label' => 'Адреса',
                'receivers' => 'Получатели',
                'sender_email' => 'Email Отправителя',
                'sender_name' => 'Имя Отправителя',
            ],
        ],
    ]
];
