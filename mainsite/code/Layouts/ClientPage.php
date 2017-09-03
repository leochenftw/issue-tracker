<?php

use SaltedHerring\Grid;

class ClientPage extends Page
{
    private static $show_in_sitetree = false;
	private static $allowed_children = array();

    /**
     * Belongs_to relationship
     * @var array
     */
    private static $defaults = array(
        'ShowInMenus'   =>  false
    );

    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Code'          =>  'Varchar(16)'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Logo'          =>  'Image',
    );

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = array(
        'Projects'      =>  'ProjectPage'
    );

    /**
     * Many_many relationship
     * @var array
     */
    private static $many_many = array(
        'ClientMembers' =>  'Client'
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab(
            'Root.Main',
            UploadField::create('Logo', 'Logo'),
            'Title'
        );
        $fields->addFieldToTab(
            'Root.Main',
            TextField::create(
                'Code',
                'Client code'
            )->setDescription('e.g. NZRDA, OMG, FBI, CIA, NSA, FFS, WTF, etc...'),
            'URLSegment'
        );

        $fields->addFieldToTab(
            'Root.Projects',
            Grid::make('Projects', 'Projects', $this->Projects(), false, 'GridFieldConfig_RelationEditor')
        );

        return $fields;
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($landingpage = Versioned::get_by_stage('ClientsLandingPage', 'Stage')->first()) {
            $this->ParentID = $landingpage->ID;
        }
    }
}

class ClientPage_Controller extends Page_Controller
{

}
