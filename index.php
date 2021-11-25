<?php
/**
 * @defgroup plugins_generic_boriSharing
 */
/**
 * @file plugins/generic/boriSharing/index.php
 *
 * Copyright (c) 2021 Agência Bori
 * Developed by Lepidus Tecnologia
 * Distributed under the GNU GPL v3. For full terms see LICENSE or https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @ingroup plugins_generic_boriSharing
 * @brief Wrapper for the Bori Sharing plugin.
 *
 */
require_once('BoriSharingPlugin.inc.php');
return new BoriSharingPlugin();