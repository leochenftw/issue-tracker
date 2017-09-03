<?php
/**
 * @file ServiceProviderAdminAdmin.php
 *
 * Left-hand-side tab : Issue Status
 * */
class ServiceProviderAdminAdmin extends ModelAdmin {
	private static $managed_models = array('ServiceProvider');
	private static $url_segment = 'service-providers';
	private static $menu_title = 'Service Providers';
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
