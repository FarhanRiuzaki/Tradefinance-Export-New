<?php
// Aside menu
return [

    'items' => [
        // Dashboard
        [
            'title'     => 'Dashboard',
            'root'      => true,
            'icon'      => 'media/svg/icons/Design/Layers.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => '/',
            'new-tab'   => false,
            'permission'=> true,
        ],
        // Custom
        [
            'section'   => 'Super Admin',
            'role'      => 'super-admins'
        ],
        [
            'title'     => 'Datatables',
            'root'      => true,
            'icon'      => 'media/svg/icons/Layout/Layout-horizontal.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'datatables',
            'new-tab'   => false,
            'role'      => 'super-admins'
        ],
        [
            'title'     => 'KTDatatable',
            'root'      => true,
            'icon'      => 'media/svg/icons/Layout/Layout-left-panel-2.svg',
            'page'      => 'ktdatatables',
            'new-tab'   => false,
            'role'      => 'super-admins'
        ],
        [
            'title'     => 'Select2',
            'root'      => true,
            'icon'      => 'media/svg/icons/Design/Interselect.svg',
            'page'      => 'select2',
            'new-tab'   => false,
            'role'      => 'super-admins'
        ],
        [
            'title'     => 'Jquery Mask',
            'root'      => true,
            'icon'      => 'la-align-center',
            'page'      => 'jquerymask',
            'new-tab'   => false,
            'role'      => 'super-admin'
        ],
        [
            'title'     => 'Icons',
            'icon'      => 'media/svg/icons/General/Attachment2.svg',
            'bullet'    => 'dot',
            'role'      => 'super-admin',
            'submenu' => [
                [
                    'title' => 'SVG Icons',
                    'page'  => 'icons/svg',
                    'role'  => 'super-admin'
                ],
                [
                    'title' => 'Flaticon',
                    'page'  => 'icons/flaticon',
                    'role'  => 'super-admin'
                ],
                [
                    'title' => 'Fontawesome 5',
                    'page'  => 'icons/fontawesome',
                    'role'  => 'super-admin'
                ],
                [
                    'title' => 'Lineawesome',
                    'page'  => 'icons/lineawesome',
                    'role'  => 'super-admin'
                ],
                [
                    'title' => 'Socicons',
                    'page'  => 'icons/socicons',
                    'role'  => 'super-admin'
                ]
            ]
        ],
        [
            'section'   => 'Master Data',
            'can'       => ['flag.index','branch.index','currency.index', 'parameter-document.index','master-product.index'],
        ],
        [
            'title'     => 'Master Flag',
            'root'      => true,
            'icon'      => 'media/svg/icons/Communication/Flag.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'flags',
            'new-tab'   => false,
            'can'       => 'flag.index'
        ],
        [
            'title'     => 'Master Branch',
            'root'      => true,
            'icon'      => 'media/svg/icons/Home/Building.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'branchs',
            'new-tab'   => false,
            'can'       => 'branch.index'
        ],
        [
            'title'     => 'Master Currency',
            'root'      => true,
            'icon'      => 'media/svg/icons/Shopping/Dollar.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'currencies',
            'new-tab'   => false,
            'can'       => 'currency.index'
        ],
        [
            'title'     => 'Master Parameter Document',
            'root'      => true,
            'icon'      => 'media/svg/icons/Files/File.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'parameter-documents',
            'new-tab'   => false,
            'can'       => 'parameter-document.index'
        ],
        [
            'title'     => 'Master Product',
            'root'      => true,
            'icon'      => 'media/svg/icons/Shopping/Credit-card.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'master-products',
            'new-tab'   => false,
            'can'       => 'master-product.index'
        ],
        [
            'section'   => 'Transaksi',
            'can'       => ['permission.index','role.index','user.index', 'product.index','audit.index','advice-maker.index','advice-approver.index', 'upload-document.index'],
        ],
        [
            'title' => 'Advice L/C',
            'icon'      => 'media/svg/icons/Design/Substract.svg',
            'custom-class' => 'svg-icon-success',
            'bullet'    => 'dot',
            'can'       => ['advice-maker.index','advice-approver.index'],
            'submenu' => [
                [
                    'title' => 'Maker Advice L/C',
                    'page'  => 'advice-makers',
                    'can'   => 'advice-maker.index'
                ],
                [
                    'title' => 'Approver Advice L/C',
                    'page'  => 'advice-approvers',
                    'can'   => 'advice-approver.index'
                ],
            ]
        ],
        [
            'title'     => 'Upload Document',
            'icon'      => 'media/svg/icons/Files/Group-folders.svg',
            'custom-class' => 'svg-icon-success',
            'bullet'    => 'dot',
            'can'       => ['upload-document.index'],
            'submenu' => [
                [
                    'title' => 'Upload Document',
                    'page'  => 'upload-documents',
                    'can'   => 'upload-document.index'
                ],
            ]
        ],
        [
            'section'   => 'Management Application',
            'can'       => ['permission.index','role.index','user.index', 'product.index','audit.index'],
        ],
        [
            'title'     => 'Product',
            'root'      => true,
            'icon'      => 'media/svg/icons/Shopping/Cart2.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'products',
            'new-tab'   => false,
            'can'       => 'product.index'
        ],
        [
            'title'     => 'Audit Trails',
            'root'      => true,
            'icon'      => 'media/svg/icons/Code/Git4.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'audits',
            'new-tab'   => false,
            'can'       => 'audit.index'
        ],
        [
            'title'     => 'Bug Reporting',
            'root'      => true,
            'icon'      => 'media/svg/icons/Code/Puzzle.svg', // or can be 'flaticon-home' or any flaticon-*
            'page'      => 'bugs',
            'new-tab'   => false,
            'can'       => 'bug.index'
        ],
        [
        'title' => 'Management Apps',
            'icon'      => 'media/svg/icons/Shopping/Settings.svg',
            'bullet'    => 'dot',
            'can'       => ['permission.index','role.index','user.index'],
            'submenu' => [
                [
                    'title' => 'Permissions',
                    'page'  => 'permissions',
                    'can'   => 'permission.index'
                ],
                [
                    'title' => 'Roles',
                    'page'  => 'roles',
                    'can'   => 'role.index'
                ],
                [
                    'title' => 'Users',
                    'page'  => 'users',
                    'can'   => 'user.index'
                ],
            ]
        ],
    ]
];
