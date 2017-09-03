<?php

class WebsiteLandingPage extends Page
{
    /**
     * Database fields
     * @var array
     */
    private static $db = array(

    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Project'   =>  'ProjectPage',
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
        'WebsitePage'
    );

    /**
     * Creating Permissions
     * @return boolean
     */
    public function canCreate($member = null)
    {
        return empty(Versioned::get_by_stage('WebsiteLandingPage', 'Stage')->first());
    }

}

class WebsiteLandingPage_Controller extends Page_Controller
{
    public function index()
    {
        return $this->renderWith(['TileLayout', 'Page']);
    }
}
