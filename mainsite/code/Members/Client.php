<?php

class Client extends Member
{
    /**
     * Belongs_many_many relationship
     * @var array
     */
    private static $belongs_many_many = array(
        'ClientPages'   =>  'ClientPage'
    );
}
