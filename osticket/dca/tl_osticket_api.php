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




$GLOBALS['TL_DCA']['tl_osticket_api'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'     => 'File',
        'enableVersioning'  => true,
        
    ),
    // Palettes
    'palettes' => array
    (
        '__selector__'      => array('osticket_use_captcha'),
        'default'           => '{api_settings_legend},osticket_api_key,osticket_api_url;{captcha_settings_legend},osticket_use_captcha'
    ),
    // Subpalettes
    'subpalettes'       => array
    (
        'osticket_use_captcha'    => 'osticket_captcha_key_public,osticket_captcha_key_secret',
    ),
    
   
    // Fields
    'fields' => array
    (
        'osticket_api_key' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_osticket_api']['osticket_api_key'],
            'inputType'     => 'text',
            'eval'          => array(
                'mandatory' => true,
            )
        ),
        'osticket_api_url' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_osticket_api']['osticket_api_url'],
            'inputType'     => 'text',
            'eval'          => array(
                'mandatory' => true,
            )
        ),
        'osticket_use_captcha' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_osticket_api']['use_captcha'],
            'inputType' => 'checkbox',
            'eval'      => array(
                'submitOnChange' => true,
                'tl_class'       => 'clr m12'
            )
        ),
        'osticket_captcha_key_public' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_osticket_api']['captcha_key_public'],
            'inputType'     => 'text',
            'eval'          => array(
                'mandatory' => true,
                'tl_class'  => 'w50'
            )
        ),
        'osticket_captcha_key_secret' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_osticket_api']['captcha_key_secret'],
            'inputType'     => 'text',
            'eval'          => array(
                'mandatory' => true,
                'tl_class'  => 'w50'
            )
        ),
    )
);
