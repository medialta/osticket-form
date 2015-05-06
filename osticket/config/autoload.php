<?php
 
/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'Contao\ModuleOsticket' => 'system/modules/osticket/modules/ModuleOsticket.php'
));
 
 
/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'mod_osticket' => 'system/modules/osticket/templates/modules'
));