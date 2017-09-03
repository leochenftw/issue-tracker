<?php

class ClientsLandingPage extends Page
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(

    );

    /**
     * Defines extension names and parameters to be applied
     * to this object upon construction.
     * @var array
     */
    private static $extensions = array(
        'Lumberjack',
    );

    /**
     * Defines the allowed child page types
     * @var array
     */
    private static $allowed_children = array(
        'ClientPage'
    );

    /**
     * Creating Permissions
     * @return boolean
     */
    public function canCreate($member = null)
    {
        return empty(Versioned::get_by_stage('ClientsLandingPage', 'Stage')->first());
    }

}

class ClientsLandingPage_Controller extends Page_Controller
{
    public function index()
    {
        return $this->renderWith(['TileLayout', 'Page']);
    }
}
