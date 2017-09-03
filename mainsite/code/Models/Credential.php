<?php

class Credential extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'AccessMethod'  =>  'Enum("SSH,FTP,cPanel,Web,Other")',
        'LoginAddress'  =>  'Varchar(2048)',
        'Account'       =>  'Varchar(256)',
        'Password'      =>  'Varchar(256)',
        'Instruction'   =>  'Text'
    );

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = array(
        'AccessMethod'  =>  'Method',
        'Account'       =>  'Username/email',
        'Password'      =>  'Password'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Environment'   =>  'Environment',
        'Provider'      =>  'ServiceProvider'
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        if ($this->Environment()->exists()) {
            $fields->removeByName(array(
                'ProviderID'
            ));
        }

        if ($this->Provider()->exists()) {
            $fields->removeByName(array(
                'EnvironmentID'
            ));
        }

        return $fields;
    }

}
