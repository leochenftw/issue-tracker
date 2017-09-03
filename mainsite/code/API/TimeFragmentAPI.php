<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class TimeFragmentAPI extends BaseRestController
{
    private $task       =   null;

    private static $allowed_actions = array (
        'post'          =>  "->isAuthenticated",
        'get'           =>  "->isAuthenticated"
    );

    public function isAuthenticated() {
        if (!empty(Member::currentUser())) {
            $taskID  =   $this->request->param('TaskID');
            if ($this->task  =   Task::get()->byID($taskID)) {
                return true;
            }
        }

        return false;
    }

    private function ConvertToHTML($content)
    {
        $segments = explode("\n", $content);
        foreach ($segments as &$item)
        {
            $item   =   trim($item);
        }

        return implode('<br />', $segments);
    }

    public function post($request)
    {
        if ($FragmentID     =   $request->param('FragmentID')) {
            $fragment       =   $this->task->TimeFragments()->byID($FragmentID);
            $end            =   time();
            $fragment->End  =   $end;
            $diff           =   $end - strtotime($fragment->Start);
            $fragment->write();
            return  [
                        'Action'        =>  'stop',
                        'MessageType'   =>  'is-success',
                        'Message'       =>  'You have logged <em>' . $this->getProperTimeDisplay($diff) . '</em> against the task.'
                    ];
        }

        $fragment           =   new TimeFragment();
        $fragment->Start    =   time();
        $fragment->TaskID   =   $this->task->ID;
        $fragment->write();

        return  [
                    'Action'            =>  'start',
                    'FragmentID'        =>  $fragment->ID,
                    'Start'             =>  time() * 1000
                ];
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

    public function get($request)
    {
        $open   =   $this->task->TimeFragments()->where('Start IS NOT NULL AND End IS NULL')->first();

        if (empty($open)) {
            return null;
        }

        return  [
                    'FragmentID'    =>  $open->ID,
                    'Start'         =>  strtotime($open->Start) * 1000,
                    'Now'           =>  time() * 1000
                ];
    }
}
