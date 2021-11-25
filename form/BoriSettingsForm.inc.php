<?php

import('lib.pkp.classes.form.Form');

class BoriSettingsForm extends Form {

	var $_contextId;

	var $_plugin;

	public function __construct($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;
		parent::__construct($plugin->getTemplateResource('settings.tpl'));
	}

	public function fetch($request, $template = null, $display = false) {
		$termsAccepted = $this->_plugin->getSetting($this->_contextId, 'terms_accepted');
		
		if(empty($termsAccepted)) $termsAccepted = false;

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign(array(
			'pluginName' => $this->_plugin->getName(),
			'termsAccepted' => $termsAccepted
		));

		return parent::fetch($request, $template, $display);
	}

	function readInputData() {
		$this->readUserVars(['termsAccepted']);
	}

	public function validate($callHooks = true) {
		if (!parent::validate($callHooks)) return false;

		$termsAccepted = $this->getData('termsAccepted');
		return $termsAccepted;
	}

	public function execute(...$functionArgs) {
		parent::execute(...$functionArgs);

		$this->_plugin->updateSetting($this->_contextId, 'terms_accepted', serialize($this->getData('termsAccepted')));
	
		$pluginSettingsDao = DAORegistry::getDAO('PluginSettingsDAO');
		$pluginSettingsDao->installSettings($this->_contextId, $this->_plugin->getName(), $this->_plugin->getContextSpecificPluginSettingsFile());
	}
}