<?php

class Repository extends DataObject
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'RepoAddress'   =>  'Varchar(2048)'
    );

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = array(
        'RepoAddress'   =>  'Repo'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Website'       =>  'WebsitePage',
        'Provider'      =>  'ServiceProvider'
    );

    public function populateDefaults()
    {
        if (!empty(Controller::curr()) && Controller::curr()->request->getVar('url') != '/dev/build') {
            if ($github     =   ServiceProvider::get()->filter(['Title' => 'Github'])->first()) {
                $this->ProviderID   =   $github->ID;
            }
        }
    }
}
