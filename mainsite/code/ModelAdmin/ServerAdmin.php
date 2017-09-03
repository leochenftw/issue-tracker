<?php
/**
 * @file ServerAdmin.php
 *
 * Left-hand-side tab : Issue Status
 * */
class ServerAdmin extends ModelAdmin {
	private static $managed_models = array('Server');
	private static $url_segment = 'servers';
	private static $menu_title = 'Servers';
	//private static $menu_icon = 'mainsite/images/issuetype_icon.png';

	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $grid->getConfig()
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldExportButton')
			->removeComponentsByType('GridFieldPrintButton')
			->addComponents(
				new GridFieldPaginatorWithShowAll(30)
			);
		return $form;
	}
}
