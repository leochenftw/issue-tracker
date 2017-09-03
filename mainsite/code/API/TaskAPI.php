<?php
use Ntb\RestAPI\BaseRestController as BaseRestController;
use SaltedHerring\Debugger as Debugger;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class TaskAPI extends BaseRestController
{
    private $project    =   null;

    private static $allowed_actions = array (
        'get'           =>  "->isAuthenticated"
    );

    public function isAuthenticated() {
        if (!empty(Member::currentUser())) {
            if ($projectID  =   $this->request->getVar('ProjectID')) {
                if ($this->project  =   ProjectPage::get()->byID($projectID)) {
                    return true;
                }
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

    public function get($request) {

        $group      =   GroupedList::create($this->project->Tasks()->sort('SprintID'))->GroupedBy('SprintID');

        $list       =   [];

        foreach ($group as $group)
        {
            $data   =   [
                            'Sprint'    =>  !empty($group->SprintID) ? (Sprint::get()->byID($group->SprintID)->Title) : 'Untitled',
                            'Tasks'     =>  []
                        ];

            foreach ($group->Children as $child)
            {
                $data['Tasks'][]        =   [
                                                'ID'            =>  $child->ID,
                                                'Title'         =>  $child->Title,
                                                'Description'   =>  $this->ConvertToHTML($child->Description)
                                            ];
            }

            $list[] =   $data;
        }

        return $list;
    }
}
