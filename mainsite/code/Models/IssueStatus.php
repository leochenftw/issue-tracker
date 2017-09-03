<?php

class IssueStatus extends DataObject
{
    private static $db = array(
        'Title'     =>  'Varchar(48)'
    );

    private static $default_sort = array(
        'SortOrder' =>  'ASC'
    );

    private static $extensions = array(
        'SortExtension'
    );

    public function requireDefaultRecords() {
        parent::requireDefaultRecords();

        foreach ($this->getDefaultStatus() as $name) {
            $existingRecord = IssueStatus::get()->filter('Title', $name)->first();

            if (!$existingRecord) {
                $status = new IssueStatus();
                $status->Title = $name;
                $status->write();
                DB::alteration_message("Issue status '$name' created", 'created');
            }
        }
    }

    protected function getDefaultStatus()
    {
        return $this->config()->get('default_status') ?: array();
    }
}
