<?php

/**
 * @category    Pimcore
 * @package     Plugin_Feed
 * @author      John Hoogstrate <jhoogstrate@schuttelaar.nl>
 * @copyright   Copyright (c) 2013 Organic Software (http://organicsoftware.nl)
 */

class Feed_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {
	
	/**
	 * @var Zend_Translate
	 */
	protected static $_translate;

	/**
	 * @return string $statusMessage
	 */
	public static function install() {
		try {
			Feed_Plugin_Install::addWebsiteSettings();
			Feed_Plugin_Install::createStaticRoutes();
		} catch(Exception $e) {
			logger::crit($e);
			return self::getTranslate()->_('feed_install_failed');
		}

		return self::getTranslate()->_('feed_installed_successfully');
	}

	/**
	 * @return string $statusMessage
	 */
	public static function uninstall() {
		try {
			// remove static routes
			Feed_Plugin_Install::removeStaticRoutes();

			return self::getTranslate()->_('feed_uninstalled_successfully');
		} catch (Exception $e) {
			Logger::crit($e);
			return self::getTranslate()->_('feed_uninstall_failed');
		}
	}

	/**
	 * CHeck if the plugin is already installed.
	 * @return boolean $isInstalled
	 */
	public static function isInstalled() {
		return Feed_Plugin_Install::hasStaticRoutes();
	}

	/**
	 * @return string
	 */
	public static function getTranslationFileDirectory() {
		return PIMCORE_PLUGINS_PATH.'/Feed/static/texts';
	}

	/**
	 * @param string $language
	 * @return string path to the translation file relative to plugin direcory
	 */
	public static function getTranslationFile($language) {
		if (is_file(self::getTranslationFileDirectory().'/'.$language.'.csv')) {
			return '/Feed/static/texts/'.$language.'.csv';
		} else {
			return '/Feed/static/texts/en.csv';
		}
	}

	/**
	 * @return Zend_Translate
	 */
	public static function getTranslate() {
		if(self::$_translate instanceof Zend_Translate) {
			return self::$_translate;
		}

		try {
			$lang = Zend_Registry::get('Zend_Locale')->getLanguage();
		} catch (Exception $e) {
			$lang = 'en';
		}

		self::$_translate = new Zend_Translate(
			'csv',
			PIMCORE_PLUGINS_PATH.self::getTranslationFile($lang),
			$lang,
			array('delimiter' => ',')
		);
		return self::$_translate;
	}

}
