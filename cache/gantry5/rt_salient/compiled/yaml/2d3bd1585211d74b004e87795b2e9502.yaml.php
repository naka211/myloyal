<?php
return [
    '@class' => 'Gantry\\Component\\File\\CompiledYamlFile',
    'filename' => 'D:/xampp/htdocs/myloyal/templates/rt_salient/gantry/theme.yaml',
    'modified' => 1452853701,
    'data' => [
        'details' => [
            'name' => 'Salient',
            'version' => '1.0.2',
            'icon' => 'paper-plane',
            'date' => 'September 11, 2015',
            'author' => [
                'name' => 'RocketTheme, LLC',
                'email' => 'support@rockettheme.com',
                'link' => 'http://www.rockettheme.com'
            ],
            'documentation' => [
                'link' => 'http://docs.gantry.org/gantry5'
            ],
            'support' => [
                'link' => 'https://gitter.im/gantry/gantry5'
            ],
            'updates' => [
                'link' => 'http://updates.rockettheme.com/themes/salient.yaml'
            ],
            'news' => [
                'link' => 'http://news.rockettheme.com/prime/themes.yaml'
            ],
            'copyright' => '(C) 2007 - 2015 RocketTheme, LLC. All rights reserved.',
            'license' => 'GPLv2',
            'description' => 'Salient Theme',
            'images' => [
                'thumbnail' => 'admin/images/preset1.png',
                'preview' => 'admin/images/preset1.png'
            ]
        ],
        'configuration' => [
            'gantry' => [
                'platform' => 'joomla',
                'engine' => 'nucleus'
            ],
            'theme' => [
                'parent' => 'rt_salient',
                'base' => 'gantry-theme://common',
                'file' => 'gantry-theme://include/theme.php',
                'class' => '\\Gantry\\Framework\\Theme'
            ],
            'fonts' => [
                'sourcesanspro' => [
                    700 => 'gantry-theme://fonts/sourcesanspro/sourcesanspro_bold/sourcesanspro-bold-webfont',
                    '700italic' => 'gantry-theme://fonts/sourcesanspro/sourcesanspro_bolditalic/sourcesanspro-bolditalic-webfont',
                    '400italic' => 'gantry-theme://fonts/sourcesanspro/sourcesanspro_italic/sourcesanspro-italic-webfont',
                    400 => 'gantry-theme://fonts/sourcesanspro/sourcesanspro_regular/sourcesanspro-regular-webfont'
                ],
                'sourcesansproextralight' => [
                    '400italic' => 'gantry-theme://fonts/sourcesansproextralight/sourcesansproextralight_italic/sourcesansproextralight-italic-webfont',
                    400 => 'gantry-theme://fonts/sourcesansproextralight/sourcesansproextralight_regular/sourcesansproextralight-regular-webfont'
                ],
                'sourcesansprolight' => [
                    '400italic' => 'gantry-theme://fonts/sourcesansprolight/sourcesansprolight_italic/sourcesansprolight-italic-webfont',
                    400 => 'gantry-theme://fonts/sourcesansprolight/sourcesansprolight_regular/sourcesansprolight-regular-webfont'
                ]
            ],
            'css' => [
                'compiler' => '\\Gantry\\Component\\Stylesheet\\ScssCompiler',
                'target' => 'gantry-theme://css-compiled',
                'paths' => [
                    0 => 'gantry-theme://scss',
                    1 => 'gantry-engine://scss'
                ],
                'files' => [
                    0 => 'salient',
                    1 => 'salient-joomla',
                    2 => 'custom'
                ],
                'persistent' => [
                    0 => 'salient'
                ],
                'overrides' => [
                    0 => 'salient-joomla',
                    1 => 'custom'
                ]
            ],
            'block-variations' => [
                'Title Variations' => [
                    'title1' => 'Title 1',
                    'title2' => 'Title 2',
                    'title3' => 'Title 3',
                    'title4' => 'Title 4',
                    'title-grey' => 'Title Grey',
                    'title-pink' => 'Title Pink',
                    'title-red' => 'Title Red',
                    'title-purple' => 'Title Purple',
                    'title-orange' => 'Title Orange',
                    'title-blue' => 'Title Blue',
                    'title-underline' => 'Title Underline',
                    'title-arrow' => 'Title Arrow'
                ],
                'Box Variations' => [
                    'box1' => 'Box 1',
                    'box2' => 'Box 2',
                    'box3' => 'Box 3',
                    'box4' => 'Box 4',
                    'box-white' => 'Box White',
                    'box-grey' => 'Box Grey',
                    'box-pink' => 'Box Pink',
                    'box-red' => 'Box Red',
                    'box-purple' => 'Box Purple',
                    'box-orange' => 'Box Orange',
                    'box-blue' => 'Box Blue'
                ],
                'Effects' => [
                    'spaced' => 'Spaced',
                    'bordered' => 'Bordered',
                    'shadow' => 'Shadow 1',
                    'shadow2' => 'Shadow 2',
                    'rounded' => 'Rounded',
                    'square' => 'Square'
                ],
                'Utility' => [
                    'g-outer-box' => 'Outer Box',
                    'disabled' => 'Disabled',
                    'align-right' => 'Align Right',
                    'align-left' => 'Align Left',
                    'title-center' => 'Centered Title',
                    'center' => 'Center',
                    'nomarginall' => 'No Margin',
                    'nopaddingall' => 'No Padding'
                ]
            ]
        ],
        'admin' => [
            'styles' => [
                'core' => [
                    0 => 'base',
                    1 => 'accent',
                    2 => 'font'
                ],
                'section' => [
                    0 => 'top',
                    1 => 'header',
                    2 => 'slideshow',
                    3 => 'showcase',
                    4 => 'feature',
                    5 => 'main',
                    6 => 'extension',
                    7 => 'bottom',
                    8 => 'footer',
                    9 => 'copyright',
                    10 => 'offcanvas'
                ],
                'configuration' => [
                    0 => 'breakpoints'
                ]
            ]
        ]
    ]
];
