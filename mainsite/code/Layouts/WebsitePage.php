<?php
use SaltedHerring\Grid;
class WebsitePage extends Page
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
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Project'       =>  'ProjectPage'
    );

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = array(
        'Repositories'  =>  'Repository',
        'Environments'  =>  'Environment',
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
            DropdownField::create(
                'ProjectID',
                'Project',
                Versioned::get_by_stage('ProjectPage', 'Stage')->map()
            )->setEmptyString('- select one -'),
            'Content'
        );

        if ($this->exists()) {
            $fields->addFieldToTab(
                'Root.Repositories',
                Grid::make('Repositories', 'Repositories', $this->Repositories(), false, 'GridFieldConfig_RelationEditor')
            );
            $fields->addFieldToTab(
                'Root.Environments',
                Grid::make('Environments', 'Environments', $this->Environments(), false, 'GridFieldConfig_RelationEditor')
            );
        }

        return $fields;
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($landingpage = Versioned::get_by_stage('WebsiteLandingPage', 'Stage')->first()) {
            $this->ParentID = $landingpage->ID;
        }
    }

}

class WebsitePage_Controller extends Page_Controller
{

}
