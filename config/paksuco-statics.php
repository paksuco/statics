<?php

return [
    "frontend" => [
        /**
         * Your frontend template layout to extend
         */
        "template_to_extend" => "layouts.app",
    ],
    "backend" => [
        /**
         * This setting defines the prefix for the package routes.
         *
         * For example if your admin page lives at /admin, the package route for
         * permission-ui roles page will be '/admin/roles', or the admin page is
         * set to '/management', you should change this to 'management' to set role
         * management routing to 'management/roles'
         */
        'admin_route_prefix' => "admin",

        /**
         * Guards for the page
         */
        "middleware" => [
            "web" => [
                "guest" => ["web"],
                "auth" => ["web", "auth"],
            ],
            "api" => [
                "guest" => ["guest:api"],
                "auth" => ["auth:api"],
            ],
        ],

        /**
         * Your admin template layout to extend
         */
        "template_to_extend" => "layouts.app",

        /**
         * Image uploads path
         */
        "image_upload_path" => public_path('/storage/uploads'),

        /**
         * If you're using Paksuco/Menu, you can set the priority here
         */
        'menu_priority' => 30,
    ],
];
