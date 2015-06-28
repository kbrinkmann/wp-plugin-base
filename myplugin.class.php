<?php 

/**
 * @desc tell something about this script...
 * @author rudi
 */



abstract class MyPlugin extends PluginTools {
	
	
	
	protected $longname;
	protected $shortname;
	protected $capability = "edit_users";		//capability (access right) to configure the plugin
	protected $slug;
	protected $optionname;						//option index to save the plugin settings
	
	/**
	 * @desc plugin absolute server directory name
	 */
	protected $plugin_dir = '';
	
	/**
	 * @desc URL to plugin directory
	 */
	protected $plugin_url = '';
	
	/**
	 * @desc absolute server path to plugin directory
	 */
	protected $plugin_path = '';

	
	public function __construct( $config = array() ) {

	    $default_config = array(
			'longname'		=> 'long_pluginname', 
			'shortname'		=> 'short_pluginname', 
			'slug'			=> 'plugin_slug',
			'optionname'	=> 'plugin_optionname',
			'pluginfile' 	=> ''
	    );
		
	    $config = wp_parse_args($config, $default_config);
		
	    
	    if($config['pluginfile'] != '') {
			
	    	$this->plugin_dir		= dirname(plugin_basename($config['pluginfile'])); 								
			$this->plugin_url		= get_option('siteurl') ."/" .PLUGINDIR. "/".$this->plugin_dir; 	
			$this->plugin_path		= ABSPATH.PLUGINDIR."/".$this->plugin_dir; 						

			$config['plugin_slug'] 	= basename(dirname($config['pluginfile']));
	    }	

	    
	    $this->longname = $config['longname'];
	    $this->shortname = $config['shortname'];
	    $this->slug = $config['plugin_slug'];
	    $this->optionname = $config['optionname'];
	    
	    
		add_action( 'admin_init', array( $this, 'save_settings' ));
		add_action( 'admin_init', array( $this, 'initiate_plugin' ));
		
		add_action("admin_menu", array($this,"create_menupage" ));
		
		
		add_action("add_meta_boxes", array($this,"create_post_metaboxes" ));
		
	}
	
	
	
	public function create_post_metaboxes($postType) {
		
	}
	
	
	public function registerSettingsPage() {
		add_options_page($this->longname, $this->shortname, $this->capability, $this->slug, array(&$this,'buildConfigPage'));
	}

	public function plugin_options_url() {
		return admin_url( 'options-general.php?page='.$this->slug );
	}
	
	
	/**
	 * @desc standard routine to save settings for a plugin, overwrite for the right purpose
	 */
	public function save_settings() {}
	
	public function initiate_plugin() {}
	
	public function create_menupage() {
		
		/*
		$page_suffix = add_menu_page(	$this->longname, 
										$this->shortname, 
										"edit_pages", 
										basename(__FILE__),
										array($this, 'create_menupage_content')
					  );
		
		add_action( "admin_head-$page_suffix", array(&$this, 'createPage_Add_Scripts') );
		*/
		
	}

	
	public function create_menupage_content() {}
	
	
	public function postbox($id, $title, $content) {
	?>
		<div id="<?php echo $id; ?>" class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class="hndle"><span><?php echo $title; ?></span></h3>
			<div class="inside">
				<?php echo $content; ?>
			</div>
		</div>
	<?php
	}	
	
	
	/**
	 * @desc create a Checkbox input field
	 */
	public function checkbox($id) {
		$options = get_option( $this->optionname );
		$checked = false;
		if ( isset($options[$id]) && $options[$id] == 1 )
			$checked = true;
		return '<input type="checkbox" id="'.$id.'" name="'.$id.'"'. checked($checked,true,false).'/>';
	}

	/**
	 * @desc create a Text input field
	 */
	public function textinput($id) {
		$options = get_option( $this->optionname );
		$val = '';
		if ( isset( $options[$id] ) )
			$val = $options[$id];
		return '<input class="text" type="text" id="'.$id.'" name="'.$id.'" size="30" value="'.$val.'"/>';
	}

	/**
	 * @desc create a dropdown field
	 */
	public function select($id, $options, $multiple = false) {
		$opt = get_option($this->optionname);
		$output = '<select class="select" name="'.$id.'" id="'.$id.'">';
		foreach ($options as $val => $name) {
			$sel = '';
			if ($opt[$id] == $val)
				$sel = ' selected="selected"';
			if ($name == '')
				$name = $val;
			$output .= '<option value="'.$val.'"'.$sel.'>'.$name.'</option>';
		}
		$output .= '</select>';
		return $output;
	}
	
	
	/**
	 * @desc creates a form table from an array of rows
	 */
	public function form_table($rows) {
		$content = '<table class="form-table">';
		$i = 1;
		foreach ($rows as $row) {
			$class = '';
			if ($i > 1) {
				$class .= 'yst_row';
			}
			if ($i % 2 == 0) {
				$class .= ' even';
			}
			$content .= '<tr id="'.$row['id'].'_row" class="'.$class.'"><th valign="top" scrope="row">';
			if (isset($row['id']) && $row['id'] != '')
				$content .= '<label for="'.$row['id'].'">'.$row['label'].':</label>';
			else
				$content .= $row['label'];
			$content .= '</th><td valign="top">';
			$content .= $row['content'];
			$content .= '</td></tr>'; 
			if ( isset($row['desc']) && !empty($row['desc']) ) {
				$content .= '<tr class="'.$class.'"><td colspan="2" class="yst_desc"><small>'.$row['desc'].'</small></td></tr>';
			}
				
			$i++;
		}
		$content .= '</table>';
		return $content;
	}	
		
	/**
	 * @desc creates the content for the specific plugin setting page, has to defined by child class
	 */
	public function buildConfigPage() { }
	
}



?>