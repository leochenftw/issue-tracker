<?php use SaltedHerring\Debugger as Debugger;

class Issue extends DataObject
{
    private static $db = array(
        'IssueIndex'        =>  'Varchar(16)',
        'Priority'          =>  'Int',
        'Title'             =>  'Varchar(256)',
        'Description'       =>  'Text',
        'IssuePageURL'      =>  'Varchar(2083)', //the current universal max length of URLs
        'OS'                =>  'Varchar(48)',
        'ScreenWidth'       =>  'Int',
        'ScreenHeight'      =>  'Int'
    );

    private static $default_sort = array(
        'SortOrder'         =>  'ASC',
        'ID'                =>  'DESC'
    );

    private static $summary_fields = array(
        'IssueIndex',
        'Created',
        'Title',
        'getStatusTitle'
    );

    private static $field_labels = array(
        'IssueIndex'        =>  'Issue Number',
        'Title'             =>  'Summary',
        'getStatusTitle'    =>  'Status'
    );

    public function getStatusTitle() {
        return $this->Status()->Title;
    }

    public function populateDefaults() {
        if (!empty(Controller::curr()) && Controller::curr()->request->getVar('url') != '/dev/build') {
            $this->ReporterID = $this->DefaultReporter();
        }
    }

    private static $has_one = array(
        'Type'              =>  'IssueType',
        'Status'            =>  'IssueStatus',
        'Project'           =>  'ProjectPage',
        'Reporter'          =>  'Reporter',
        'Assignee'          =>  'Member'
    );

    private static $has_many = array(
        'Screenshots'       =>  'Image',
        'Comments'          =>  'Comment'
    );

    private static $many_many = array(
        'LinkedIssues'      =>  'Issue'
    );

    private static $belongs_many_many = array(
        'LinkedinIssue'     =>  'Issue',
        'inScrumbBoard'     =>  'ScrumBoard'
    );

    private static $extensions = array(
        'SortExtension'
    );

    public function onBeforeWrite() {
        parent::onBeforeWrite();

        if (empty($this->ID)) {
            $project = Versioned::get_by_stage('ProjectPage', 'Live')->byID($this->ProjectID);
            $this->IssueIndex = $project->ProjectKey . '-' . $project->NextIssueIndex;
            $this->StatusID = IssueStatus::get()->filter('Title', 'Open')->first()->ID;
            $project->NextIssueIndex = $project->NextIssueIndex+1;
            $project->writeToStage('Stage');
            $project->publish('Stage', 'Live');
        }

        if (empty($this->ReporterID)) {
            $this->ReporterID = $this->DefaultReporter();
        }
    }

    public function DefaultReporter() {
        if ($member = Member::currentUser()) {
            return $member->ReporterProfile()->ID;
        }
        return null;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->fieldByName('Root.Main.Title')->setTitle('Summary');
        $fields->fieldByName('Root.Main.Description')->setTitle('Details');
        $fields->addFieldToTab(
            'Root.Main',
            DropdownField::create(
                'OS',
                'Operating System',
                Config::inst()->get('Issue', 'OS')
            )->setEmptyString('- I don\'t know -')
        );
        return $fields;
    }
}
