<?php

$post_id = get_the_ID();
$post_type = get_post_type( $post_id );

// Meeting Date and Time
$date_raw = get_post_meta( $post_id, '_wordpress_meetings_meeting_date', true );
$meeting_date = mysql2date( get_option('date_format'), $date_raw, false);

$start_time_raw = get_post_meta( $post_id, '_wordpress_meetings_meeting_start_time', true );
if ( ! empty( $start_time_raw ) ) {
	$meeting_start_time = mysql2date( get_option('time_format'), $date_raw . ' ' . $start_time_raw . ':00', false);
	$meeting_time = $meeting_start_time;
}

$end_time_raw = get_post_meta( $post_id, '_wordpress_meetings_meeting_end_time', true );
if ( ! empty( $end_time_raw ) ) {
	$meeting_end_time = mysql2date( get_option('time_format'), $date_raw . ' ' . $end_time_raw . ':00', false);
}

if ( ! empty( $meeting_start_time ) AND ! empty( $meeting_end_time ) ) {
	$meeting_time .= '&mdash;' . $meeting_end_time;
}

// Meeting Meta
$organization = get_the_term_list( get_the_ID(), 'organization', '<span class="organization tag">', ', ', '</span>' );
$meeting_type = get_the_term_list( $post_id, 'meeting_type', '<span class="meeting-type tag">', ', ', '</span>' );
$meeting_tags = get_the_term_list( $post_id, 'meeting_tag', '<span class="meeting-tag tag">', ', ', '</span>' );

// Proposal Meta
$proposal_status = get_the_term_list( $post_id, 'proposal_status', '<span class="proposal-status tag">', ', ', '</span>' );
$date_accepted_raw = get_post_meta( $post_id, '_wordpress_meetings_proposal_date_accepted', true );
if ( ! empty( $date_accepted_raw ) ) {
	$date_accepted = mysql2date( get_option('date_format'), $date_accepted_raw, false);
}
$date_effective_raw = get_post_meta( $post_id, 'wordpress_meetings_proposal_date_effective', true );
if ( ! empty( $date_effective_raw ) ) {
	$date_accepted = mysql2date( get_option('date_format'), $date_effective_raw, false);
}

?>

<?php if ( 'meeting' == $post_type ) : ?>

	<?php if ( ! empty( $meeting_date ) ) : ?>
		<div class="meta meeting-meta">
			<span class="meta-label"><?php _e( 'Date:', 'wordpress-meetings' ); ?></span> <?php echo $meeting_date; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $meeting_time ) ) : ?>
		<div class="meta meeting-meta">
			<span class="meta-label"><?php _e( 'Time:', 'wordpress-meetings' ); ?></span> <?php echo $meeting_time; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $meeting_type ) ) : ?>
		<div class="meta meeting-meta">
			<span class="meta-label"><?php _e( 'Type:', 'wordpress-meetings' ); ?></span> <?php echo $meeting_type; ?>
		</div>
	<?php endif; ?>

<?php endif; ?>

<?php if ( ! empty( $organization ) ) : ?>

	<div class="meta meeting-meta">
		<span class="meta-label"><?php _e( 'Organization:', 'wordpress-meetings' ); ?></span> <?php echo $organization; ?>
	</div>

<?php endif; ?>

<?php if ( ! empty( $meeting_tags ) ) : ?>
	<div class="meta meeting-meta">
		<span class="meta-label"><?php _e( 'Tags:', 'wordpress-meetings' ); ?></span> <?php echo $meeting_tags; ?>
	</div>
<?php endif; ?>

<?php if ( 'proposal' == $post_type ) : ?>

	<?php if ( ! empty( $proposal_status ) ) : ?>
		<div class="meta meeting-meta">
			<span class="meta-label"><?php _e( 'Status:', 'wordpress-meetings' ); ?></span> <?php echo $proposal_status; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $date_accepted ) ) : ?>
		<div class="meta meeting-meta">
			<span class="meta-label"><?php _e( 'Date Appoved:', 'wordpress-meetings' ); ?></span> <?php echo $date_accepted; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $date_effective ) ) : ?>
		<div class="meta meeting-meta">
			<span class="meta-label"><?php _e( 'Date Effective:', 'wordpress-meetings' ); ?></span> <?php echo $date_effective; ?>
		</div>
	<?php endif; ?>

<?php endif; ?>
