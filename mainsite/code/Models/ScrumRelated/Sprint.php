<?php

class Sprint extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Title'     =>  'Varchar(2048)',
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Epic'      =>  'Epic',
        'Project'   =>  'ProjectPage'
    );

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = array(
        'Tasks'     =>  'Task'
    );

    /**
     * Defines extension names and parameters to be applied
     * to this object upon construction.
     * @var array
     */
    private static $extensions = array(
        'SortExtension'
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields =   parent::getCMSFields();
        $source =   function()
                    {
                    	return Epic::get()->map()->toArray();
                    };
        $field  =   DropdownField::create('EpicID', 'Epic', $source())->setEmptyString('- select one -');
        $field->useAddNew('Epic', $source);
        $fields->addFieldToTab(
            'Root.Main',
            $field
        );

        if (empty($this->ProjectID)) {
            $fields->removeByName(array(
                'ProjectID'
            ));
        }

        if (empty($this->EpicID)) {
            $fields->removeByName(array(
                'EpicID'
            ));
        }

        return $fields;
    }

    /**
     * Event handler called after writing to the database.
     */
    public function onAfterWrite()
    {
        parent::onAfterWrite();
        if (empty($this->ProjectID) && $this->Tasks()->exists()) {
            $this->ProjectID = $this->Tasks()->first()->ProjectID;
            $this->write();
        }
    }
}
