<?php

class Environment extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Title'         =>  'Enum("Dev,UAT,Prod")',
        'Content'       =>  'HTMLText',
        'OtherAliases'  =>  'Text'
    );

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = array(
        'Credentials'   =>  'Credential',
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'InternalURL'   =>  'Link',
        'WebsiteURL'    =>  'Link',
        'OnServer'      =>  'Server',
        'Website'       =>  'WebsitePage'
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->fieldByName('Root.Main.Title')->setTitle('Type');

        $fields->addFieldToTab('Root.Main', LinkField::create('InternalURLID', 'Internal URL'), 'Content');
        $fields->addFieldToTab('Root.Main', LinkField::create('WebsiteURLID', 'Website URL'), 'Content');

        return $fields;
    }
}
