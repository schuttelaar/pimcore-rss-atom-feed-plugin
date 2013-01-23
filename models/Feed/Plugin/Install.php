<?php

/**
 * @category    Pimcore
 * @package     Plugin_Feed
 * @author      John Hoogstrate <jhoogstrate@schuttelaar.nl>
 * @copyright   Copyright (c) 2013 Organic Software (http://organicsoftware.nl)
 */
class Feed_Plugin_Install {

	/**
	 * Add the required properties to the website settings.
	 * The lastest version of Pimcore at this tim eis 1.4.8.
	 * There is currently no API for saving website settings, so we must write to the XML-file manually.
	 * This functionality might break in future versions.
	 * Please check the status of this issue at the following page:
	 * http://www.pimcore.org/issues/browse/PIMCORE-1842
	*/
	public static function addWebsiteSettings() {

		//we need to write the website settings to the XML file manually because currently Pimcore has no API for this!
		$websiteConfigPath = PIMCORE_CONFIGURATION_DIRECTORY . '/website.xml';
		
		$websiteConfig = new Zend_Config_Xml($websiteConfigPath, null, array('allowModifications' => true));
		$pluginSettings = self::getSettingsConfig();

		foreach($pluginSettings as $key => $value) {
			if(!isset($websiteSettings->$key)) {
				$websiteConfig->$key = $value;
			}
		}

		$writer = new Zend_Config_Writer_Xml(array(
			'config' => $websiteConfig,
			'filename' => $websiteConfigPath
		));
		$writer->write();

		//remove old settings from cache, forces a reload from the file system
		Pimcore_Model_Cache::clearTags(array('output', 'system', 'website_config'));
	}

	public static function createStaticRoutes() {
		$conf = self::getStaticRoutesConfig();
		foreach($conf->routes->route as $def) {
			$route = Staticroute::create();
			$route->setName($def->name);
			$route->setPattern($def->pattern);
			$route->setReverse($def->reverse);
			$route->setModule($def->module);
			$route->setController($def->controller);
			$route->setAction($def->action);
			$route->setVariables($def->variables);
			$route->setPriority($def->priority);
			$route->save();
		}
	}

	public static function removeStaticRoutes() {
		$conf = self::getStaticRoutesConfig();
		foreach($conf->routes->route as $def) {
			$route = Staticroute::getByName($def->name);
			if($route) {
				$route->delete();
			}
		}
	}

	/**
	 * Check if at least one static route is present.
	 * This is the minimum required for the plugin to work.
	 * @return bool TRUE if at least one static route is present.
	*/
	public static function hasStaticRoutes() {
		$conf = self::getStaticRoutesConfig();
		foreach($conf->routes->route as $def) {
			$route = Staticroute::getByName($def->name);
			if($route) {
				return true;
			}
		}

		return false;
	}

	protected static function getStaticRoutesConfig() {
		return new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/Feed/install/staticroutes.xml');		
	}

	protected static function getSettingsConfig() {
		return new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/Feed/install/website.xml');		
	}
}
