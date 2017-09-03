<?php

class CalendarController extends Page_Controller
{
    public function index()
    {
        return $this->renderWith(['Calendar', 'Page']);
    }

    public function Title()
    {
        return 'Calendar';
    }
}
