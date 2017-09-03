<?php

class ContactExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Address'   =>  'Text',
        'EmailAddr' =>  'Varchar(256)',
        'Landline'  =>  'Varchar(64)',
        'Mobile'    =>  'Varchar(32)'
    );

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $fields->addFieldsToTab(
            'Root.ContactDetails',
            array(
                TextareaField::create(
                    'Address',
                    'Address'
                ),
                EmailField::create(
                    'EmailAddr',
                    'Contact email'
                ),
                TextField::create(
                    'Landline',
                    'Landline'
                ),
                TextField::create(
                    'Mobile',
                    'Mobile'
                )
            )
        );
        return $fields;
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($this->owner->hasField('Email')) {
            $this->owner->EmailAddr = $this->owner->Email;
        }
    }
}
