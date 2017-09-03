<?php use SaltedHerring\Debugger as Debugger; use SaltedHerring\Grid as Grid;

class ProjectPage extends Page
{
    private static $show_in_sitetree = false;
	private static $allowed_children = array();

    private static $db = array(
        'NextIssueIndex'        =>  'Int',
        // 'ProjectKey'            =>  'Varchar(8)',
        'ProjectTTD'            =>  'SS_Datetime'
    );

    /**
     * Defines extension names and parameters to be applied
     * to this object upon construction.
     * @var array
     */
    private static $extensions  =   array(
        'ContactExtension'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Website'               =>  'WebsitePage',
        'Client'                =>  'ClientPage'
    );

    private static $has_many = array(
        'Issues'                =>  'Issue',
        'ScrumBoards'           =>  'ScrumBoards',
        'Epics'                 =>  'Epic',
        'Sprints'               =>  'Sprint',
        'Tasks'                 =>  'Task'
    );

    private static $many_many = array(
        'ProjectAdministrators' =>  'Member',
        'Develoeprs'            =>  'Member',
        'ProjectManagers'       =>  'Member'
    );

    private static $defaults = array(
        'NextIssueIndex'        =>  1,
        'ShowInMenus'           =>  false
    );

    public function canAcceptIssue() {
        if (!empty($this->ProjectTTD)) {
            if ($this->ProjectTTD < time()) {
                return false;
            }
        }
        return true;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', $ttd = DatetimeField::create('ProjectTTD', 'Project support ends on'), 'URLSegment');
        $ttd->setConfig('datavalueformat', 'yyyy-MM-dd HH:mm');
        $ttd->getDateField()->setConfig('showcalendar', 1);

        if ($this->exists()) {
            $fields->addFieldToTab(
                'Root.Epics',
                Grid::make('Epics', 'Epics', $this->Epics(), true, 'GridFieldConfig_RelationEditor')
            );

            $fields->addFieldToTab(
                'Root.Sprints',
                Grid::make('Sprints', 'Sprints', $this->Sprints(), true, 'GridFieldConfig_RelationEditor')
            );

            $fields->addFieldToTab(
                'Root.Tasks',
                Grid::make('Tasks', 'Tasks', $this->Tasks(), true, 'GridFieldConfig_RelationEditor')
            );

            $fields->addFieldToTab(
                'Root.Issues',
                Grid::make('Issues', 'Issues', $this->Issues(), true, 'GridFieldConfig_RelationEditor')
            );

            $fields->addFieldToTab(
                'Root.ProjectAdministrators',
                Grid::make('ProjectAdministrators', 'Project Admins', $this->ProjectAdministrators(), false, 'GridFieldConfig_RelationEditor')
            );
            $fields->addFieldToTab(
                'Root.Developers',
                Grid::make('Developers', 'Developers', $this->Develoeprs(), false, 'GridFieldConfig_RelationEditor')
            );
            $fields->addFieldToTab(
                'Root.ProjectManagers',
                Grid::make('ProjectManagers', 'Project Managers', $this->ProjectManagers(), false, 'GridFieldConfig_RelationEditor')
            );
        }

        $fields->addFieldToTab(
            'Root.Main',
            DropdownField::create(
                'WebsiteID',
                'Website',
                Versioned::get_by_stage('WebsitePage', 'Stage')->map()
            )->setEmptyString('- select one -'),
            'Content'
        );


        return $fields;
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if ($landingpage = Versioned::get_by_stage('ProjectLandingPage', 'Stage')->first()) {
            $this->ParentID = $landingpage->ID;
        }
    }

    /**
     * Creating Permissions
     * @return boolean
     */
    public function canCreate($member = null)
    {
        return !empty(Versioned::get_by_stage('ProjectLandingPage', 'Stage')->first());
    }

}

class ProjectPage_Controller extends Page_Controller {
    private static $allowed_actions = array(
        'IssueForm'
    );

    public function IssueForm()
    {
        return new IssueForm($this);
    }
}
