<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture


    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Start',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Projekt finale by Ylva',
        ],

        // This is a menu item
        'user'  => [
            'text'  => 'Användare',
            'url'   => $this->di->get('url')->create('user'),
            'title' => 'Användare',

            //add submenu
            'submenu' => [

            'items' => [

                // This is a menu item of the submenu
                'item 0'  => [
                    'text'  => 'Lägg till användare',
                    'url'   => $this->di->get('url')->create('users2/add'),
                    'title' => 'AddUser',
                    'class' => 'italic',
                ],

                // This is a menu item of the submenu
                'item 2'  => [
                    'text'  => 'Se alla användare',
                    'url'   => $this->di->get('url')->create('users2/list'),
                    'title' => 'SeeUsers',
                    'class' => 'italic',
                ],

                // This is a menu item of the submenu
                'item 3'  => [
                    'text'  => 'Uppdatera användare',
                    'url'   => $this->di->get('url')->create('users2/update'),
                    'title' => 'UpdateUsers',
                    'class' => 'italic',
                ],

                // This is a menu item of the submenu
                'item 4'  => [
                    'text'  => 'Mest aktiva användare',
                    'url'   => $this->di->get('url')->create('users2/ActiveUser'),
                    'title' => 'ActiveUser',
                    'class' => 'italic',
                ],
            ],

            ],

            ],

        // This is a menu item
        'questions'  => [
            'text'  => 'Frågor',
            'url'   => $this->di->get('url')->create('questions'),
            'title' => 'Questions',

            //this is a submenu
            'submenu' => [

                //theese are the submenu items
                'items' => [

                    // This is a menu item of the submenu
                    'item 0'  => [
                        'text'  => 'Visa alla frågor',
                        'url'   => $this->di->get('url')->create('questions/list'),
                        'title' => 'Questions',
                        'class' => 'italic',
                    ],

                    // This is a menu item of the submenu
                    'item 1'  => [
                        'text'  => 'Visa sist ställda frågor',
                        'url'   => $this->di->get('url')->create('questions/theLatestQ'),
                        'title' => 'Questions',
                        'class' => 'italic',
                    ],

                    // This is a menu item of the submenu
                    'item 2'  => [
                        'text'  => 'Ställ fråga',
                        'url'   => $this->di->get('url')->create('questions/addQuestion'),
                        'title' => 'Questions',
                        'class' => 'italic',
                    ],

                ],

            ],
        ],

        // This is a menu item
        'tags'  => [
            'text'  => 'Tags',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'Tags',
        ],

        // This is a menu item
        'about'  => [
            'text'  => 'Om',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'About',
        ],

        // This is a menu item
        'login/logout'  => [
            'text'  => 'Login/logout',
            'url'   => $this->di->get('url')->create('login'),
            'title' => 'Login/logout',
        ],

    ],


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
