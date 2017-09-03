<?php use SaltedHerring\Debugger as Debugger;

class MemberExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'DailyReportTo'     =>  'Text'
    );

    private static $has_one = array(
        'ReporterProfile'   =>  'Reporter'
    );

    /**
     * Has_many relationship
     * @var array
     */
    private static $has_many = [
        'TimeFragments'     =>  'TimeFragment'
    ];

    private static $belongs_many_many = array(
        'AsProjectAdmin'    =>  'ProjectPage.ProjectAdministrators',
        'AsDeveloper'       =>  'ProjectPage.Developers',
        'AsProjectManager'  =>  'ProjectPage.ProjectManagers',
        'AsCustomer'        =>  'ProjectPage.Customers'
    );

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $fields->addFieldToTab(
            'Root.Main',
            TextField::create(
                'DailyReportTo',
                'Report to email(s)'
            )->setDescription('use comma(,) to separate emails')
        );
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (empty($this->owner->ReporterProfileID)) {
            $reporter_profile = Reporter::get()->filter(array('Email' => $this->owner->Email))->first();
            if (!$reporter_profile) {
                $reporter_profile = new Reporter();
                $reporter_profile->Email = $this->owner->Email;
                $reporter_profile->write();
            }
            $this->owner->ReporterProfileID = $reporter_profile->ID;
        }
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();

        if(!empty($this->owner->ReporterProfileID)) {
            $this->owner->ReporterProfile()->MemberID = $this->owner->ID;
            $this->owner->ReporterProfile()->write();
        }

    }

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        foreach ($this->getDefaultGroups() as $name) {
            $existingRecord = Group::get()->filter('Title', $name)->first();

            if (!$existingRecord) {
                $group = new Group();
                $group->Title = $name;
                $group->write();
                DB::alteration_message("Group '$name' created", 'created');
            }
        }
    }

    protected function getDefaultGroups()
    {
        return $this->owner->config()->get('default_groups') ?: array();
    }

    public function getReportingEmails()
    {
        $report_to          =   $this->owner->Email == 'defaultadmin' ? ['leochenftw@gmail.com'] : [$this->owner->Email];
        if (!empty($this->owner->DailyReportTo)) {
            $report_to      =   array_merge($report_to, explode(',', $this->owner->DailyReportTo));
            array_walk($report_to, function(&$item)
            {
                $item       =   trim($item);
            });
        }

        $report_to      =   implode(',', $report_to);

        return $report_to;
    }
}
