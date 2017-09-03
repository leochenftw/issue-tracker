<?php

class Server extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Title'         =>  'Varchar(32)',
        'Content'       =>  'HTMLText',
        'PrimaryIP'     =>  'Varchar(128)',
        'SecondIP'      =>  'Varchar(128)'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Provider'      =>  'ServiceProvider'
    );

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = array(
        'Environments'  =>  'Environment'
    );
}
