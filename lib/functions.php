<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ut_taxonomy_name($name){
    if(empty($name)) return;
    $taxonomy_name = str_replace ( '-', '_', str_replace(' ', '_', strtolower($name) ) );
    $taxonomy_slug = 'rce_user_' . $taxonomy_name;
    return $taxonomy_slug;
}
function top_tags( $taxonomy = FALSE ) {
        if( !$taxonomy){
            $taxonomy = array();
            $ut_taxonomies = get_site_option( 'ut_taxonomies' );
            if ( empty($ut_taxonomies ) || !is_array( $ut_taxonomies) ) return;
            foreach ( $ut_taxonomies as $ut_taxonomy ){
                $taxonomy[] = ut_taxonomy_name($ut_taxonomy['name']);
            }
        }
        $tags = get_terms($taxonomy, array(
                'orderby'    => 'count',
                'hide_empty' => 0
         ));
        if (empty($tags))
                return;
        $counts = $tag_links = array();
        foreach ( (array) $tags as $tag ) {
                $counts[$tag->name] = $tag->count;
                $tag_links[$tag->name] = get_tag_link( $tag->term_id );
        }
        asort($counts);
        $counts = array_reverse( $counts, true );
        $i = 0;
        $output = '';
        foreach ( $counts as $tag => $count ) {
                $i++;
                $tag_link = esc_url($tag_links[$tag]);
                $tag = str_replace(' ', '&nbsp;', esc_html( $tag ));
                if($i < 11){
                        $output .= "<a href=\"$tag_link\">$tag ($count)</a>";
                }else{
                    break;
                }
        }
        return $output;
}
add_filter( 'taxonomy_template', 'get_custom_taxonomy_template' );
function get_custom_taxonomy_template($template) {
    
    $taxonomy = get_query_var('taxonomy');

    if (strpos($taxonomy,'rce_user_') !== false) {
        $taxonomy_template = RCE_UT_TEMPLATES ."user-taxonomy-template.php";
        $file_headers = @get_headers($taxonomy_template);
        if( $file_headers[0] != 'HTTP/1.0 404 Not Found'){
           return $taxonomy_template;
        }
        
    }
   return $template; 
}