<?php

class WS_Init_Filters extends WS_Filter_Set {

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		parent::__construct( array(
			'upload_mimes' 			=> 'svg_mime_types',
			'tiny_mce_before_init'	=> 'my_format_TinyMCE',
            'wp_get_attachment_url' => 'rewrite_cdn_url',
            'gform_ajax_iframe_content' => 'add_gform_domain',
            'gform_form_tag'            => array('process_form_action_url', 10, 2)
    	));
	}

    function add_gform_domain( $data ) {
        echo "<script>document.domain = 'leadershipri.org';</script>\n";
        return $data;
    }

    function process_form_action_url( $form_tag, $form ) {

        $form_tag = preg_replace( "|action='(.*?)'|", "action='http://cms.leadershipri.org/gravityformsapi/forms/" . $form['id'] . "/submissions'", $form_tag );

        return $form_tag;
    }

	public function svg_mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function my_format_TinyMCE( $in ) {
		$in['remove_linebreaks'] = false;
		$in['gecko_spellcheck'] = false;
		$in['keep_styles'] = true;
		$in['accessibility_focus'] = true;
		$in['tabfocus_elements'] = 'major-publishing-actions';
		$in['media_strict'] = false;
		$in['paste_remove_styles'] = false;
		$in['paste_remove_spans'] = false;
		$in['paste_strip_class_attributes'] = 'none';
		$in['paste_text_use_dialog'] = true;
		$in['wpeditimage_disable_captions'] = true;
		//$in['plugins'] = 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen';
		//$in['content_css'] = get_template_directory_uri() . "/editor-style.css";
		$in['wpautop'] = true;
		$in['apply_source_formatting'] = false;
		$in['block_formats'] = "Paragraph=p; Heading 3=h3; Heading 4=h4; Heading 5=h5";
		$in['toolbar1'] = 'formatselect,bold,italic,underline,bullist,numlist,link,unlink';
		$in['toolbar2'] = '';
		$in['toolbar3'] = '';
		$in['toolbar4'] = '';
		return $in;
	}

    /**
     * Rewrite attachment URL from the base CMS form to the desired CDN form.
     *
     * @filter 'wp_get_attachment_url'
     * @param $original string the original attachment URL
     * @return the updated CDN url.
     */
    public function rewrite_cdn_url( $original ) {

        $trailing_string = '/wp-content/uploads/';
        $cms_url =  get_option( 'siteurl' );
        $cdn_url = get_option('cdn_url');

        if ( ! empty( $cdn_url ) ) {

            return str_replace( $cms_url . $trailing_string, $cdn_url . '/', $original );

        } else {

            return $original;

        }

    }

}

new WS_Init_Filters();
?>
