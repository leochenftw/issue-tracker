<?php

class IssueType extends DataObject
{
    private static $db = array(
        'Title'     =>  'Varchar(16)'
    );

    private static $has_one = array(
        'Icon'      =>  'Image'
    );

    public function requireDefaultRecords() {
        parent::requireDefaultRecords();

        foreach ($this->getDefaultTypes() as $name) {
            $existingRecord = IssueType::get()->filter('Title', $name)->first();

            if (!$existingRecord) {
                $type = new IssueType();
                $type->Title = $name;
                $type->write();
                DB::alteration_message("Issue type '$name' created", 'created');
            }
        }
    }

    protected function getDefaultTypes()
    {
        return $this->config()->get('default_types') ?: array();
    }
}
