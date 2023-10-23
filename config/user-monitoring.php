<?php

return [
    /**
     * Fallback delete days.
     *
     * Delete records after a certain number of days.
     * 0 will disable deletion of records.
     * This will be used when individual monitor has "delete_days" set to null
     *
     * This requires the PruneUserMonitoringRecords command to be added to your task scheduling
     * @see docs for details
     */
    'delete_days'   => 0,

    /*
     * Configurations.
     */
    'config' => [
        'routes' => [
            'file_path' => 'routes/user-monitoring.php',
        ],
    ],

    /*
     * User properties.
     *
     * You can customize the user guard, table, foreign key, and ... .
     */
    'user' => [
        /*
         * User model.
         */
        'model' => 'App\Models\User',

        /*
         * Foreign Key column name.
         */
        'foreign_key' => 'user_id',

        /*
         * Users table name.
         */
        'table' => 'users',

        /*
         * The correct guard.
         */
        'guard' => 'web',

        /*
         * If you are using uuid or ulid you can change it for the type of foreign_key.
         *
         * When you are using ulid or uuid, you need to add related traits into the models.
         */
        'foreign_key_type' => 'id', // uuid, ulid, id
    ],

    /*
     * Visit monitoring configurations.
     */
    'visit_monitoring' => [
        'table' => 'visits_monitoring',

        /*
         * If you want to disable visit monitoring, you can change it to false.
         */
        'turn_on' => true,

        /*
         * You can specify pages not to be monitored.
         */
        'except_pages' => [
            // 'home',
        ],

        /*
         * You can specify routes or paths to not be monitored.
         *
         * This check is done using "$request->is($pattern) || $request->routeId($pattern
         */
        'should_skip' => [
//            'user-monitoring/visits-monitoring',
            'user-monitoring.*',
        ],

        /**
         * Delete records after a certain number of days.
         * 0 will disable deletion of records for this monitor.
         * null will cause the fallback delete_days from above to be used.
         *
         * This requires the PruneUserMonitoringRecords command to be added to your task scheduling
         * @see docs for details @todo
         */
        'delete_days'   => null,
    ],

    /*
     * Action monitoring configurations.
     */
    'action_monitoring' => [
        'table' => 'actions_monitoring',

        /*
         * Monitor actions.
         *
         * You can set true/false for monitor actions like (store, update, and ...).
         */
        'on_store'      => true,
        'on_update'     => true,
        'on_destroy'    => true,
        'on_read'       => true,
        'on_restore'    => false, // Release for next version :)
        'on_replicate'  => false, // Release for next version :)

        /**
         * Delete records after a certain number of days.
         * 0 will disable deletion of records for this monitor.
         * null will cause the fallback delete_days from above to be used.
         *
         * This requires the PruneUserMonitoringRecords command to be added to your task scheduling
         * @see docs for details @todo
         */
        'delete_days'   => null,
    ],

    /*
     * Authentication monitoring configurations.
     */
    'authentication_monitoring' => [
        'table' => 'authentications_monitoring',

        /*
         * If you want to delete authentications-monitoring rows when the user is deleted from the users table you can set true or false.
         */
        'delete_user_record_when_user_delete' => true,

        /*
         * You can set true/false for monitor login or logout.
         */
        'on_login' => true,
        'on_logout' => true,

        /**
         * Delete records after a certain number of days.
         * 0 will disable deletion of records for this monitor.
         * null will cause the fallback delete_days from above to be used.
         *
         * This requires the PruneUserMonitoringRecords command to be added to your task scheduling
         * @see docs for details @todo
         */
        'delete_days'   => null,
    ],
];
