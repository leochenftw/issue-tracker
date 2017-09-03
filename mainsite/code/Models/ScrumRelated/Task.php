<?php

class Task extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'JobNumber'         =>  'Varchar(128)',
        'Title'             =>  'Varchar(2048)',
        'Description'       =>  'Text',
    );

    private static $default_sort = array(
        'SortOrder'         =>  'ASC',
        'ID'                =>  'DESC'
    );

    public function populateDefaults()
    {
        $this->RequestedByID    =   Member::currentUserID();
    }

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Project'           =>  'ProjectPage',
        'RequestedBy'       =>  'Member',
        'Assignee'          =>  'Member',
        'Sprint'            =>  'Sprint'
    );

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = array(
        'SortOrder'         =>  'Priority',
        'Title'             =>  'Task'
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
     * Has_many relationship
     * @var array
     */
    private static $has_many = array(
        'TimeFragments'     =>  'TimeFragment'
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields =   parent::getCMSFields();
        // $source =   function()
        //             {
        //             	// return Sprint::get()->map()->toArray();
        //                 return $this->Project()->Sprints()->map()->toArray();
        //             };
        // $field  =   DropdownField::create('SprintID', 'Sprint', $source())->setEmptyString('- select one -');
        // $field->useAddNew('Sprint', $source);
        $fields->addFieldToTab(
            'Root.Main',
            // $field
            DropdownField::create('SprintID', 'Sprint', $this->Project()->Sprints()->map())->setEmptyString('- select one -')
        );
        return $fields;
    }
}
