<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file ReportAPI.php
 *
 * Controller to present the data from forms.
 * */
class ReportAPI extends BaseRestController
{
    private $date       =   null;
    private $member     =   null;

    private static $allowed_actions = array (
        'post'          =>  "->isAuthenticated"
    );

    public function isAuthenticated() {
        if ($this->member = Member::currentUser()) {
            if ($this->date = $this->request->param('Date')) {
                if ($csrf = $this->request->postVar('csrf')) {
                    return $csrf == Session::get('SecurityID');
                }
            }
        }

        return false;
    }

    public function post($request)
    {
        $groups         =   GroupedList::create($this->member->TimeFragments()->filter(['StartDate' => $this->date])->sort('TaskID'))->GroupedBy('TaskID');
        $list           =   [];
        $dict           =   [];
        $time_summary   =   0;

        foreach ($groups as $group)
        {
            $children   =   $group->Children;
            $task       =   Task::get()->byID($group->TaskID);
            $client     =   $this->getClient($task);
            $data       =   [
                                'JobNumber' =>  $task->JobNumber,
                                'Title'     =>  $task->Title,
                                'Duration'  =>  0
                            ];
            foreach ($children as $child)
            {
                $data['Duration']   +=  $child->getDuration();
            }

            $time_summary           +=  $data['Duration'];
            $data['Duration']       =   $this->getProperTimeDisplay($data['Duration']);

            if (empty($dict[$client])) {
                $dict[$client]      =   [];
            }

            $dict[$client][]        =   new ArrayData($data);
        }

        foreach ($dict as $key => $value)
        {
            $list[]                 =   new ArrayData([
                                            'Title'     =>  $key,
                                            'List'      =>  new ArrayList($value)
                                        ]);
        }

        $time_summary   =   $this->getProperTimeDisplay($time_summary);
        $list           =   new ArrayList($list);
        $date           =   new DateTime($this->date);
        $date           =   $date->format('d/m/Y');
        $email          =   new EndDayReport($date, $list, $time_summary);
        $email->send();

        return  [
                    'Message'   =>  'The end of day report has been sent'
                ];
    }

    private function getClient(&$task)
    {
        $client          =   'Unknown Client';
        if ($task->Project()->exists()) {
            $project = $task->Project();
            if ($project->Client()->exists()) {
                $client  =  $project->Client()->Title;
            }
        }

        return $client;
    }

    private function getProperTimeDisplay($seconds)
    {
        if ($seconds >= 3600) {
            $hour       =   round(($seconds / 3600) * 100) / 100;
            return $hour . ' hour' . ($hour > 1 ? 's' : '');
        } else if ($seconds >= 60) {
            $minute     =   round(($seconds / 60) * 100) / 100;
            return $minute . ' minute' . ($minute > 1 ? 's' : '');
        }

        return $seconds . ' second'  . ($seconds > 1 ? 's' : '');
    }
}
