<?php
namespace AlphastarCourseCatalog;
define( 'ASA_COURSE_CATALOG_VERSION', '1.0.0.0' );
/**
 * Plugin Name:  AlphaStar Course Catalog
 * Description: The plugin lists the AlphaStar courses that are registered in Ninja Table
 * Version: 1.0.0
 * Author : Alphastar
 * Author URI: https://alphastar.academy.com
 * Plugin Name: Event Espresso Moodle Synchronization (EE4.9.13+)
*/
use AlphastarCourseCatalog\AsaCourseCatalogTemplate;

require 'Asa_Course_Template.class.php';

class AsaCourseCatalog
{

    private static $_instance= null;
    public function __construct()
    {
        self::$_instance = true;
        AsaCourseCatalogTemplate::getInstance();
    }

    
     /**
     * getInstance function to maintain singleton pattern.
     */
    public static function getInstance()
    {
        if (self::$_instance== null) {
            self::$_instance= new AsaCourseCatalog();
        }
        return self::$_instance;
    }

}


AsaCourseCatalog::getInstance();