<?php
/**
 * @file plugins/generic/boriSharing/BoriSharingPlugin.inc.php
 *
 * Copyright (c) 2021 Lepidus Tecnologia
 * Distributed under the GNU GPL v3. For full terms see LICENSE or https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @class BoriSharingPlugin
 * @ingroup plugins_generic_boriSharing
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');
class BoriSharingPlugin extends GenericPlugin {

	public function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path, $mainContextId);
		
		if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;
		if ($success && $this->getEnabled($mainContextId)) {
			error_log("Bori");
		}
		return $success;
	}

	public function getDisplayName() {
		return __('plugins.generic.boriSharing.displayName');
	}

	public function getDescription() {
		return __('plugins.generic.boriSharing.description');
	}

}
