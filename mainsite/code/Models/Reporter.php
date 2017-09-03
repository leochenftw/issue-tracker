<?php use SaltedHerring\Debugger as Debugger;

class Reporter extends DataObject {
    private static $db = array(
        'Email'     =>  'Varchar(256)'
    );

    private static $summary_fields = array(
        'Email'
    );

    private static $has_one = array(
        'Member'    =>  'Member'
    );

    public function Title() {
        return $this->Email;
    }
}
