<?php
use SaltedHerring\Debugger;
class EndDayReport extends SaltedEmail
{
    public function __construct($date, $list, $total) {
        $sender             =   $this->getMembername() . '<' . Member::currentUser()->Email . '>';

        if ($sender         ==  'defaultadmin') {
            $sender         =   'Leo Chen <leo@leochen.co.nz>';
        }

        $from               =   $sender;
        $to                 =   Member::currentUser()->getReportingEmails();
        $subject            =   'End of day report - ' . $date;

        parent::__construct($from, $to, $subject);

        $this->setTemplate('EndDayReport');

        $this->populateTemplate(new ArrayData(
            array (
                'baseURL'   =>  Director::absoluteBaseURL(),
                'Title'     =>  $subject,
                'Tasks'     =>  $list,
                'Total'     =>  $total,
                'Member'    =>  Member::currentUser()
            )
        ));
	}

    private function getMembername()
    {
        $member             =   Member::currentUser();
        return $member->FirstName . (!empty($member->Surname) ? (' ' . $member->Surname) : '');
    }
}
