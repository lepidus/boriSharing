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
		$userAuthKey = $this->_plugin->getSetting($this->_contextId, 'user_auth_key');
		$disableAPI = $this->_plugin->getSetting($this->_contextId, 'disable_API');
		
		if(empty($termsAccepted)) $termsAccepted = false;

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign(array(
			'pluginName' => $this->_plugin->getName(),
			'termsAccepted' => $termsAccepted,
			'userAuthKey' => $userAuthKey,
			'disableAPI' => $disableAPI
		));

		return parent::fetch($request, $template, $display);
	}

	function readInputData() {
		$termsAccepted = $this->_plugin->getSetting($this->_contextId, 'terms_accepted');

        if (empty($termsAccepted)) {
            $this->readUserVars(['termsAccepted', 'userAuthKey', 'disableAPI']);
        } else{
			$this->readUserVars(['disableAPI']);
		}
	}

	public function validate($callHooks = true) {
		if (!parent::validate($callHooks)) return false;

		$termsAccepted = $this->getData('termsAccepted');
		if (empty($termsAccepted)) {
			return $termsAccepted;
        } else{
			$disableAPI = $this->getData('disableAPI');
            return $disableAPI;
        }
		
	}

	public function execute(...$functionArgs) {
		parent::execute(...$functionArgs);

		$termsAccepted = $this->_plugin->getSetting($this->_contextId, 'terms_accepted');
		if (empty($termsAccepted)){
			$this->_plugin->updateSetting($this->_contextId, 'terms_accepted', $this->getData('termsAccepted'));
			$this->_plugin->updateSetting($this->_contextId, 'user_auth_key', $this->getData('userAuthKey'));
			$this->_plugin->updateSetting($this->_contextId, 'disable_API', $this->getData('disableAPI'));
		} else{
			$this->_plugin->updateSetting($this->_contextId, 'disable_API', $this->getData('disableAPI'));
		}
	
		$pluginSettingsDao = DAORegistry::getDAO('PluginSettingsDAO');
		$pluginSettingsDao->installSettings($this->_contextId, $this->_plugin->getName(), $this->_plugin->getContextSpecificPluginSettingsFile());
	}
}