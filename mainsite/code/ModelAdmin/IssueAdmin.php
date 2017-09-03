<?php
/**
 * @file IssueAdmin.php
 *
 * Left-hand-side tab : Issue Status
 * */
class IssueAdmin extends ModelAdmin {
	private static $managed_models = array('Issue', 'IssueStatus', 'IssueType');
	private static $url_segment = 'issues';
	private static $menu_title = 'Issues';
	//private static $menu_icon = 'mainsite/images/issuetype_icon.png';

	public function getEditForm($id = null, $fields = null) {

		$form = parent::getEditForm($id, $fields);

		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        if ($this->modelClass != 'IssueType') {
    		$grid->getConfig()
    			->removeComponentsByType('GridFieldPaginator')
    			->removeComponentsByType('GridFieldExportButton')
    			->removeComponentsByType('GridFieldPrintButton')
    			->addComponents(
    				new GridFieldPaginatorWithShowAll(30),
    				new GridFieldOrderableRows('SortOrder')
    			);
        } else {
            $grid->getConfig()
    			->removeComponentsByType('GridFieldPaginator')
    			->removeComponentsByType('GridFieldExportButton')
    			->removeComponentsByType('GridFieldPrintButton')
    			->addComponents(
    				new GridFieldPaginatorWithShowAll(30)
    			);
        }
		return $form;
	}
}
