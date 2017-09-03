<?php

class TimeFragment extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'Start'             =>  'SS_Datetime',
        'End'               =>  'SS_Datetime',
        'StartDate'         =>  'Date',
        'EndDate'           =>  'Date'
    );
    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Task'              =>  'Task',
        'DoneBy'            =>  'Member'
    );

    public function populateDefaults()
    {
        $this->DoneByID     =   Member::currentUserID();
    }

    /**
     * Event handler called before writing to the database.
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (empty($this->TaskID)) {
            $task               =   new Task();
            $task->write();

            $this->TaskID       =   $task->ID;
        }

        $this->StartDate    =   $this->Start;
        $this->EndDate      =   $this->End;
    }

    public function getDuration()
    {
        if (!empty($this->End) && !empty($this->Start)) {
            return abs(strtotime($this->End) - strtotime($this->Start));
        }

        return 0;
    }
}
