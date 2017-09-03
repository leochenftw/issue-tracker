<?php

class ServiceProvider extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Title'     =>  'Varchar(128)',
        'Content'   =>  'HTMLText'
    );

    /**
     * Defines extension names and parameters to be applied
     * to this object upon construction.
     * @var array
     */
    private static $extensions = array(
        'ContactExtension'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_many = array(
        'Server'        =>  'Server',
        'Credentials'   =>  'Credential'
    );
}
