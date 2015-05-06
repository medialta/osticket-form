<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   osticket
 * @author    Medialta
 * @license   GNU/LGPL
 * @copyright Medialta 2015
 */

/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 5, array
(
    'osticket' => array
    (
        'tables' => array('tl_osticket_api'),
        'icon'   => 'system/modules/osticket/assets/icon.png'
    )
));

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['miscellaneous'], 0, array
(
    'osticket' => 'ModuleOsticket'
));