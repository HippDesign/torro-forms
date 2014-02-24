<?php
/*
 * Core Component
 *
 * This class initializes the component.
 *
 * @author rheinschmiede.de, Author <kontakt@rheinschmiede.de>
 * @package PluginName/Admin
 * @version 1.0.0
 * @since 1.0.0
 * @license GPL 2
 * 

  Copyright 2013 (kontakt@rheinschmiede.de)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

if ( !defined( 'ABSPATH' ) ) exit;

class SurveyVal_Core extends SurveyVal_Component{
	/**
	 * Initializes the Component.
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->name = 'SurveyValCore';
		$this->title = __( 'Core', 'surveyval-locale' );
		$this->description = __( 'Core functions of the SurveyVal Plugin', 'surveyval-locale' );
		$this->turn_off = FALSE;
		
		$this->slug = 'survey';
		
		add_action( 'init', array( $this, 'custom_post_types' ), 11 );
		add_filter( 'the_content', array( $this, 'the_content' ) );
		
		parent::__construct();
		
	} // end constructor
	
	public function the_content( $content ){
		global $post, $surveyval_global;
		
		if( 'surveyval' != $post->post_type )
			return $content;
		
		$survey = new SurveyVal_Survey( $post->ID );
		$content = $survey->get_survey_html();
		
		return $content;
	}
	
	/**
	 * Creates Custom Post Types for surveys
	 * @since 1.0.0
	 */	
	public function custom_post_types(){
		/**
		 * Categories
		 */
		$args_taxonomy = array(
			'show_in_nav_menus' => TRUE,
		    'hierarchical' => TRUE,
		    'labels' => array(
				'name' => _x( 'Categories', 'taxonomy general name', 'surveyval-locale' ),
			    'singular_name' => _x( 'Category', 'taxonomy singular name', 'surveyval-locale' ),
			    'search_items' =>  __( 'Search Categories', 'surveyval-locale' ),
			    'all_items' => __( 'All Categories', 'surveyval-locale' ),
			    'parent_item' => __( 'Parent Category', 'surveyval-locale' ),
			    'parent_item_colon' => __( 'Parent Category:', 'surveyval-locale' ),
			    'edit_item' => __( 'Edit Category', 'surveyval-locale' ), 
			    'update_item' => __( 'Update Category', 'surveyval-locale' ),
			    'add_new_item' => __( 'Add New Category', 'surveyval-locale' ),
			    'new_item_name' => __( 'New Category', 'surveyval-locale' ),
			    'menu_name' => __( 'Categories', 'surveyval-locale' ),
			),
		    'show_ui' => TRUE,
		    'query_var' => TRUE,
		    'rewrite' => TRUE,
		);
		
		register_taxonomy( 'surveyval-categories', array( 'surveyval' ), $args_taxonomy );
		
		/**
		 * Post Types
		 */
		$args_post_type = array(
			'labels' => array(
				'name' => __( 'SurveyVal', 'surveyval-locale' ),
				'singular_name' => __( 'Survey', 'surveyval-locale' ),
				'all_items' => __( 'All Surveys', 'surveyval-locale' ),
				'add_new_item' => __( 'Add new Survey', 'surveyval-locale' ),
				'edit_item' => __( 'Edit Survey', 'surveyval-locale' ),
				'new_item' => __( 'Add new Survey', 'surveyval-locale' ),
				'view_item' => __( 'View Survey', 'surveyval-locale' ),
				'search_items' => __( 'Search Survey', 'surveyval-locale' ),
				'not_found' => __( 'No Survey found', 'surveyval-locale' ),
				'not_found_in_trash' => __( 'No Survey found', 'surveyval-locale' )
			),
			'public' => TRUE,
			'has_archive' => TRUE,
			'supports' => array( 'title' ),
			'show_in_menu'  => 'ComponentSurveyValAdmin',
			'show_in_nav_menus' => FALSE,
			'rewrite' => array(
	            'slug' => $this->slug,
	            'with_front' => TRUE
            )
				
		); 
		
		register_post_type( 'surveyval', $args_post_type );
	}

	public function includes(){
		include( SURVEYVAL_COMPONENTFOLDER . '/core/surveyval.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/shortcodes.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/models/survey.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/models/question-type.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/question-types/text.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/question-types/onechoice.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/question-types/multiplechoice.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/question-types/range.php' );
		include( SURVEYVAL_COMPONENTFOLDER . '/core/question-types/range-emotional.php' );
	}
	
}

$SurveyVal_Core = new SurveyVal_Core();