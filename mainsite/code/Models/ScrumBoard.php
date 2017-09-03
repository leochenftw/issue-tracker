<?php

class ScrumBoard extends DataObject {
    private static $db = array(
        'Deadline'      =>  'SS_Datetime',
        'Status'        =>  'Enum("Planned,Started,Completed")'
    );

    private static $has_one = array(
        'Project'       =>  'ProjectPage'
    );

    private static $many_many = array(
        'Issues'        =>  'Issue'
    );

    private static $extensions = array(
        'SortExtension'
    );

    private static $default_sort = array(
        'SortOrder'     =>  'ASC',
        'ID'            =>  'DESC'
    );
}
