<?php

class Epic extends DataObject
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
        'Project'   =>  'ProjectPage'
    );

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = array(
        'Sprints'   =>  'Sprint',
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
        $fields = parent::getCMSFields();
        if (!$this->Project()->exists()) {
            $fields->removeByName(array(
                'ProjectID'
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
        if (!$this->Project()->exists() && $this->Sprints()->exists() > 0) {
            $this->ProjectID = $this->Sprints()->first()->ProjectID;
            $this->write();
        }
    }
}
