<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class CalendarAPI extends BaseRestController
{
    private $year       =   null;
    private $month      =   null;
    private $date       =   null;

    private static $allowed_actions = array (
        'get'           =>  "->isAuthenticated"
    );

    public function isAuthenticated() {
        if (!empty(Member::currentUser())) {
            if (($this->year = $this->request->param('year')) && ($this->month = $this->request->param('month'))) {
                return true;
            }
        }

        return false;
    }

    public function get($request) {

        if (!empty($this->request->param('date'))) {
            $this->date     =   $this->request->param('date');
            $groups         =   GroupedList::create(TimeFragment::get()->filter(['DoneByID' => Member::currentUserID(), 'StartDate' => $this->year . '-' . $this->month . '-' . $this->date])->sort('TaskID'))->GroupedBy('TaskID');

            $list           =   [
                                    'title' =>  $this->year . '-' . $this->month . '-' . $this->date,
                                    'tasks' =>  [],
                                    'csrf'  =>  Session::get('SecurityID'),
                                    'date'  =>  $this->year . '-' . $this->month . '-' . $this->date
                                ];

            foreach ($groups as $group)
            {
                $children   =   $group->Children;

                $task       =   Task::get()->byID($group->TaskID);
                $client     =   $this->getClient($task);
                $data       =   [
                                    'id'        =>  $group->TaskID,
                                    'client'    =>  $client,
                                    'badge'     =>  !empty($task->JobNumber) ? $task->JobNumber : $client,
                                    'title'     =>  $task->Title,
                                    'duration'  =>  0
                                ];
                foreach ($children as $child)
                {
                    $data['duration'] += $child->getDuration();
                }

                $list['tasks'][]    =   $data;
            }

            return $list;

        }

        $this->date         =   '01';
        $start_date         =   $this->year . '-' . $this->month . '-' . $this->date;
        $end_date           =   $this->year . '-' . $this->month . '-31';

        $groups             =   GroupedList::create(TimeFragment::get()->filter(['DoneByID' => Member::currentUserID(), 'StartDate:GreaterThanOrEqual' => $start_date, 'EndDate:LessThanOrEqual' => $end_date])->sort('StartDate'))->GroupedBy('StartDate');

        $list               =   [];

        foreach ($groups as $group)
        {
            $date           =   $group->StartDate;
            $children       =   GroupedList::create($group->Children->List->sort('TaskID'))->GroupedBy('TaskID')->limit(5);

            foreach ($children as $task_group)
            {
                $task       =   Task::get()->byID($task_group->TaskID);
                $client     =   $this->getClient($task);
                $list[]     =   [
                                    'date'      =>  $date,
                                    'client'    =>  $client,
                                    'badge'     =>  !empty($task->JobNumber) ? $task->JobNumber : $client,
                                    'title'     =>  $task->Title,
                                    'isbutton'  =>  false
                                ];
            }

            if ($task_group->Children->count() > 3) {

                $list[]     =   [
                                    'date'      =>  $group->StartDate,
                                    'isbutton'  =>  true,
                                    'title'     =>  '...and more'
                                ];
            }
        }

        return $list;
    }

    private function getClient(&$task)
    {
        $client   =   'N/A';
        if ($task->Project()->exists()) {
            $project = $task->Project();
            if ($project->Client()->exists()) {
                $client  =  $project->Client()->Code;
            }
        }

        return $client;
    }
}
