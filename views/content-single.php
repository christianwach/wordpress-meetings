<?php

/* 
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */

$post_id = get_the_ID();
$post_type = get_post_type( $post_id );

// Meeting Meta
// $meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post_id, 'meeting_date', true ) ) );
$meeting_date = ( get_field( 'meeting_date' ) ) ? date_i18n( get_option( 'date_format' ), strtotime( get_field( 'meeting_date' ) ) ) : '' ;
$meeting_type = get_the_term_list( $post_id, 'meeting_type', '<span class="category">', ', ', '</span>' );
$meeting_tags = get_the_term_list( $post_id, 'meeting_tag', '<span class="tags">', ', ', '</span>' );

// Proposal Meta
$approval_date = $meeting_date;
$effective_date = ( get_field( 'proposal_date_effective' ) ) ? date_i18n( get_option( 'date_format' ), strtotime( get_field( 'proposal_date_effective' ) ) ) : '' ;
$proposal_status = get_the_term_list( $post_id, 'proposal_status', '<span class="tags">', ', ', '</span>' );

// Associated Content
$connected_agenda = get_posts( array(
  'connected_type' => 'meeting_to_agenda',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
  'suppress_filters' => false
) );

$connected_summary = get_posts( array(
  'connected_type' => 'meeting_to_summary',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
  'suppress_filters' => false
) );
    
$connected_proposal = get_posts( array(
  'connected_type' => 'meeting_to_proposal',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
  'suppress_filters' => false
) );
    
    
/* 
 * Before the_content()
 * Content that appears before the_content()
 */

$meeting_pre_content = '';

if( 'meeting' == $post_type ) {

    $meeting_pre_content .= '<p class="meta"><span class="meta-label">' . __( 'Date:', 'meeting' ) . '</span> ' . $meeting_date . '</p>';
    $meeting_pre_content .= '<p class="meta"><span class="meta-label">' . __( 'Type:', 'meeting' ) . '</span> ' . $meeting_type . '</p>';

}

if( 'proposal' == $post_type ) {
    $meeting_pre_content .= ( $meeting_date ) ? '<p class="meta"><span class="meta-label">' . __( 'Date Appoved:', 'meeting' ) . '</span> <time>' . $meeting_date . '</time></p>' : '';
    $meeting_pre_content .= ( $effective_date ) ? '<p class="meta"><span class="meta-label">' . __( 'Date Effective:', 'meeting' ) . '</span> <time>' . $effective_date . '</time></p>' : '';
    $meeting_pre_content .= ( $proposal_status ) ? '<p class="meta"><span class="meta-label">' . __( 'Status:', 'meeting' ) . '</span> ' . $proposal_status . '</p>' : '';
}


if( !empty( $connected_agenda ) || !empty( $connected_summary ) || !empty( $connected_proposal ) ) {

    $meeting_pre_content .= '<ul class="connected-content">';

    foreach( $connected_agenda as $agenda ) {
        $post_type_obj = get_post_type_object( get_post_type( $agenda->ID ) );
        $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';
        $meeting_pre_content .= '<li class="agenda-link"><a href="' . get_post_permalink( $agenda->ID ) . '">';
        $meeting_pre_content .= ( $post_type_name ) ? $post_type_name : $agenda->post_title;
        $meeting_pre_content .= '</a></li>'; 
    }

    foreach( $connected_summary as $summary ) {
        $post_type_obj = get_post_type_object( get_post_type( $summary->ID ) );
        $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';
        $meeting_pre_content .= '<li class="summary-link"><a href="' . get_post_permalink( $summary->ID ) . '">';
        $meeting_pre_content .= ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';
        $meeting_pre_content .= '</a></li>'; 
    }

    if( 'proposal' == get_post_type() ) {

      foreach( $connected_proposal as $proposal ) {
          $post_type_obj = get_post_type_object( get_post_type( $proposal->ID ) );
          $post_type_name = __( 'Meeting', 'meeting' );
          $meeting_pre_content .= '<li class="meeting-link"><a href="' . get_post_permalink( $proposal->ID ) . '">';
          $meeting_pre_content .= $post_type_name;
          $meeting_pre_content .= '</a></li>'; 
      }

    }


    if( 'meeting' == get_post_type() && count( $connected_proposal ) > 0 ) {

        $meeting_pre_content .= '<li class="proposal-link"><a href="#proposals">' . __( 'Proposal(s)', 'meeting' ) . '</a></li>';

    }

    $meeting_pre_content .= '</ul>';

}

$meeting_pre_content .= '';

$meeting_pre_content .= '<div class="post-content">';

/* 
 * After the_content()
 * Content that appears after the_content()
 */

$meeting_post_content = '';
$meeting_post_content .= '<footer class="post-footer">';

if( $meeting_tags ) {
    $meeting_post_content .= '<p class="tags meta"><span class="meta-label">' . __( 'Tags:', 'meeting' ) . '</span> ';
    $meeting_post_content .= $meeting_tags;
    $meeting_post_content .= '</p>';
}

if( !empty( $connected_proposal ) ) {

    $meeting_post_content .= '<h3 id="proposals">'. __( 'Proposals', 'meeting' ) . '</h3>';

    $meeting_post_content .= '<ul class="proposal-link">';

    foreach ( $connected_proposal as $proposal ) {

        $meeting_post_content .= '<li class="proposal-link"><a href="' . get_post_permalink( $proposal->ID ) . '">';
        $meeting_post_content .= $proposal->post_title;
        $meeting_post_content .= '</a></li>';
        
    }

    $meeting_post_content .= '</ul>';  

}

$meeting_post_content .= '</footer>';
$meeting_post_content .= '</div>';


?>