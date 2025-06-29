<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'profile' => 'c,r,u,d',
            'experience' => 'c,r,u,d',
            'education' => 'c,r,u,d',
            'project' => 'c,r,u,d',
            'skill' => 'c,r,u,d',
            'language' => 'c,r,u,d',
            'certificate' => 'c,r,u,d',
            'award' => 'c,r,u,d',
            'reference' => 'c,r,u,d',
            'hobby' => 'c,r,u,d',
            'volunteering' => 'c,r,u,d',
            'publication' => 'c,r,u,d',
            'social_link' => 'c,r,u,d',
        ],
        'user' => [
            'profile' => 'c,r,u,d',
            'experience' => 'c,r,u,d',
            'education' => 'c,r,u,d',
            'project' => 'c,r,u,d',
            'skill' => 'c,r,u,d',
            'language' => 'c,r,u,d',
            'certificate' => 'c,r,u,d',
            'award' => 'c,r,u,d',
            'reference' => 'c,r,u,d',
            'hobby' => 'c,r,u,d',
            'volunteering' => 'c,r,u,d',
            'publication' => 'c,r,u,d',
            'social_link' => 'c,r,u,d',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
