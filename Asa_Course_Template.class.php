<?php
namespace AlphastarCourseCatalog;
define( 'ASA_COURSE_CATALOG_PATH', plugin_dir_path( __FILE__ ));
define( 'ASA_COURSE_CATALOG_URL', plugin_dir_url( __FILE__ ));
define( 'DS', '/');

class AsaCourseCatalogTemplate
{

    private static $_instance= null;

    public function __construct()
    {
        self::$_instance = true; 

        add_shortcode( 'COURSE_CATALOG', array($this, 'process_shortcode') );    

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 10);
        add_action( 'wp_ajax_get_levels', array($this,  'get_levels'));
        add_action( 'wp_ajax_nopriv_get_levels',  array($this, 'get_levels' ));  
    } 

     // AJAX request handler
    public function get_levels() 
    {    
        $subject = $_POST['subject']  === 'cs' ? 'cs-' : $_POST['subject'];
        $subject_level  = array(      
        array("subject"=>"math","level"=>"Elementary School"),
        array("subject"=>"math","level"=>"AMC 8/MathCounts"),
        array("subject"=>"math","level"=>"AMC 10/12"),
        array("subject"=>"math","level"=>"AIME"),
        array("subject"=>"math","level"=>"USA(J)MO"),
        array("subject"=>"cs-","level"=>"Programming"),
        array("subject"=>"cs-","level"=>"USACO Bronze"),
        array("subject"=>"cs-","level"=>"USACO Silver"),
        array("subject"=>"cs-","level"=>"USACO Gold"),
        array("subject"=>"cs-","level"=>"USACO Platinum"),
        array("subject"=>"physics","level"=>"F=ma"),
        array("subject"=>"math,physics,cs-","level"=>"Master")
        );

        $filterBy = $subject; // or Finance etc.
        $newLevels = array();
        $res = '';
        if ($filterBy !== 'Show All') {
                $newLevels = array_filter($subject_level, function ($var) use ($filterBy) {
                    return (strpos($var['subject'], $filterBy) !== false);
                });
        }
        
        $res .=  '<option value="" class="ee_filter_show_all">Show All</option>';
            $resLevels = empty($newLevels) ? $subject_level : $newLevels;
            
        foreach ($resLevels as $level) {
           $level_code =  AsaCourseCatalogTemplate::get_level_code($level['level']);
                $res .=  '<option value="' . $level_code . '" class="' .$level_code  . '">'. $level['level']. '</option>';
        }

        echo $res;
        wp_die();
    }  

   /**
     *    enqueue_scripts - Load the scripts and css
     *
     * @access    public
     * @return    void
     */
    public function enqueue_scripts($hook)
    {
        wp_enqueue_script( 'get-levels-ajax', plugins_url( '/scripts/asa_course_catalog_template.js', __FILE__ ), array('jquery') );
     
        // in JavaScript, object properties are accessed as ajax_object.ajax_url
        wp_localize_script( 'get-levels-ajax', 'level_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ))
        );

        wp_register_style(
            'asa-course-catalog',
            ASA_COURSE_CATALOG_PATH . 'css' . DS . 'asa_course_catalog_template.css'
        );
    }

    public static function process_shortcode($attributes = array())
    {
        
        // make sure $attributes is an array
        $attributes = array_merge(
        // defaults
            array(
                'template_file'        => 'asa-course-catalog-template.template.php', //Default template file
                'limit'                => 1000,
                'show_expired'         => false,
                'month'                => null,
                'category_slug'        => null,
                'category_filter'      => null,
                'category_filter_text' => null,
                'order_by'             => 'start_date',
                'sort'                 => 'DESC',
                'footable'             => null,
                'table_style'          => 'standalone',
                'table_sort'           => null,
                'table_paging'         => null,
                'table_pages'          => 10,
                'table_striping'       => null,
                'table_search'         => null,
                'show_all_datetimes'   => false,
                'show_venues'          => true,
            ),
            (array)$attributes
        );
      
            //FooTable Styles
            wp_register_style(
                'footable-core',
                ASA_COURSE_CATALOG_URL . 'css' . DS . 'footable.core.css'
            );
            wp_enqueue_style('footable-core');
            wp_register_style(
                'footable-' . $attributes['table_style'],
                ASA_COURSE_CATALOG_URL . 'css' . DS . 'footable.' . $attributes['table_style'] . '.css'
            );
            wp_enqueue_style('footable-' . $attributes['table_style']);
            //FooTable Scripts
            wp_register_script(
                'footable',
                ASA_COURSE_CATALOG_URL . 'scripts' . DS . 'footable.js',
                array('jquery'),
                ASA_COURSE_CATALOG_VERSION,
                true
            );
            // enqueue scripts
            wp_enqueue_script('footable');
            //FooTable Sorting
       
                wp_register_script(
                    'footable-sort',
                    ASA_COURSE_CATALOG_URL . 'scripts' . DS . 'footable.sort.js',
                    array('jquery'),
                    ASA_COURSE_CATALOG_VERSION,
                    true
                );
                wp_enqueue_script('footable-sort');
            
            //FooTable Striping
   
                wp_register_script(
                    'footable-striping',
                    ASA_COURSE_CATALOG_URL . 'scripts' . DS . 'footable.striping.js',
                    array('jquery'),
                    ASA_COURSE_CATALOG_VERSION,
                    true
                );
                wp_enqueue_script('footable-striping');
            
            //FooTable Pagination
        
                wp_register_script(
                    'footable-paginate',
                    ASA_COURSE_CATALOG_URL . 'scripts' . DS . 'footable.paginate.js',
                    array('jquery'),
                    ASA_COURSE_CATALOG_VERSION,
                    true
                );
                wp_enqueue_script('footable-paginate');
 
            //FooTable Filter
    
                wp_register_script(
                    'footable-filter',
                    ASA_COURSE_CATALOG_URL . 'scripts' . DS . 'footable.filter.js',
                    array('jquery'),
                    ASA_COURSE_CATALOG_VERSION,
                    true
                );
                wp_enqueue_script('footable-filter');
          
        
        
 
        $attributes['template_file'] = plugin_dir_path( __FILE__ ) . DS .'templates' . DS . 'asa-course-catalog-template.template.php';
 
        $template_path = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $attributes['template_file']);


        ob_start();
        include($template_path);
        $events_table_template =  ob_get_clean();
        // now reset the query and postdata
        wp_reset_query();
        wp_reset_postdata();
        

        return $events_table_template;
    }

     /**
     * temporary copy of \EES_Shortcode::sanitize_attributes()
     * for backwards compatibility sake
     *
     * @param array $attributes
     * @param array $custom_sanitization
     * @return array
     */
    private function sanitize_the_attributes(array $attributes, $custom_sanitization = array())
    {
        foreach ($attributes as $key => $value) {
            // is a custom sanitization callback specified ?
            if (isset($custom_sanitization[$key])) {
                $callback = $custom_sanitization[$key];
                if ($callback === 'skip_sanitization') {
                    $attributes[$key] = $value;
                    continue;
                } else if (function_exists($callback)) {
                    $attributes[$key] = $callback($value);
                    continue;
                }
            }
            switch (true) {
                case $value === null :
                case is_int($value) :
                case is_float($value) :
                    // typical booleans
                case in_array($value, array(true, 'true', '1', 'on', 'yes', false, 'false', '0', 'off', 'no'), true) :
                    $attributes[$key] = $value;
                    break;
                case is_string($value) :
                    $attributes[$key] = sanitize_text_field($value);
                    break;
                case is_array($value) :
                    $attributes[$key] = $this->sanitize_the_attributes($attributes);
                    break;
                default :
                    // only remaining data types are Object and Resource
                    // which are not allowed as shortcode attributes
                    $attributes[$key] = null;
                    break;
            }
        }
        return $attributes;
    }

    public function getEvents($subject, $level, $search_filter, $orderBy)
    {
        global $wpdb;
		$tablename1 = $wpdb->prefix."posts";
        $tablename2 = $wpdb->prefix."ninja_table_items";
		$event_fields = array();
        $query = " SELECT d.value
					FROM `$tablename1` p
					LEFT JOIN `$tablename2` d ON p.ID = d.table_id				
					where p.post_type= 'ninja-table' 
					and p.post_title ='CourseCatalog'
					and JSON_VALUE(value,'$.active') = 1
					and (JSON_VALUE(value,'$.subject') = '$subject' or '$subject' = '')	
					and (LOWER(JSON_VALUE(value,'$.name')) LIKE '%$search_filter%' or 
					     LOWER(JSON_VALUE(value,'$.code')) LIKE '%$search_filter%' or '$search_filter' = '')		
					and (JSON_VALUE(value,'$.level') = '$level' or '$level' = '')" . $orderBy;

        $events = $wpdb->get_results($query, 'ARRAY_A');

		foreach ($events as $event) {
			$fields =  json_decode($event["value"], true);
			$event_fields[] = $fields;
		}
   
        return $event_fields;
    }

	public static function get_level_code($level) {

		$levels = 
		array(      
		  array("level_code"=>"mc1","level"=>"Elementary School"),
		  array("level_code"=>"mc2","level"=>"AMC 8/MathCounts"),
		  array("level_code"=>"mc3","level"=>"AMC 10/12"),
		  array("level_code"=>"mc4","level"=>"AIME"),
		  array("level_code"=>"mc5","level"=>"USA(J)MO"),
		  array("level_code"=>"cs2","level"=>"Programming"),
		  array("level_code"=>"cc2","level"=>"USACO Bronze"),
		  array("level_code"=>"cc3","level"=>"USACO Silver"),
		  array("level_code"=>"cc4","level"=>"USACO Gold"),
		  array("level_code"=>"cc5","level"=>"USACO Platinum"),
		  array("level_code"=>"fma","level"=>"F=ma"),
		  array("level_code"=>"master","level"=>"Master")
		);
	  
		 $res_levels = array_filter($levels, function ($var) use ($level) {
			 return ($var['level'] === $level);
		 });

		 $result = null;
		 foreach ($res_levels as $res) {
			$result = $res['level_code'];
			break;
		}
		 return $result;
	  }


	  public function get_level($level_code) {

		$levels = 
		array(      
		  array("level_code"=>"mc1","level"=>"Elementary School"),
		  array("level_code"=>"mc2","level"=>"AMC 8/MathCounts"),
		  array("level_code"=>"mc3","level"=>"AMC 10/12"),
		  array("level_code"=>"mc4","level"=>"AIME"),
		  array("level_code"=>"mc5","level"=>"USA(J)MO"),
		  array("level_code"=>"cs2","level"=>"Programming"),
		  array("level_code"=>"cc2","level"=>"USACO Bronze"),
		  array("level_code"=>"cc3","level"=>"USACO Silver"),
		  array("level_code"=>"cc4","level"=>"USACO Gold"),
		  array("level_code"=>"cc5","level"=>"USACO Platinum"),
		  array("level_code"=>"fma","level"=>"F=ma"),
		  array("level_code"=>"master","level"=>"Master")
		);
	  
		 $res_levels = array_filter($levels, function ($var) use ($level_code) {
			 return ($var['level_code'] === $level_code);
		 });

		 $result = null;
		 foreach ($res_levels as $res) {
			$result = $res['level'];
			break;
		}
		 return $result;
	  }

	  function sortorder($fieldname){

		parse_str( $_SERVER['QUERY_STRING'], $params);
		$params['order_by'] = $fieldname;
		$params['sort'] = "asc";
	
		if(isset($_GET['order_by']) && $_GET['order_by'] == $fieldname){
			if(isset($_GET['sort']) && $_GET['sort'] == "asc"){
				$params['sort'] = "desc";
			}
		}
		$sorturl = "?". http_build_query($params);
		return $sorturl;
	}

    /**
     * getInstance function to maintain singleton pattern.
     */
    public static function getInstance()
    {
        if (self::$_instance== null) {
            self::$_instance= new AsaCourseCatalogTemplate();
        }
        return self::$_instance;
    }
}
