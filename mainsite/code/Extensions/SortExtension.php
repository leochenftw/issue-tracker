<?php

class SortExtension extends DataExtension
{
    private static $db = array(
        'SortOrder'     =>  'Int'
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->removeByName('SortOrder');
    }
}
