<div class="wrap">
	<div id="icon-venues" class="icon32"><br></div>
	<h2><?php
		echo $post_type_object->labels->name;
		echo sprintf( ' <a class="add-new-h2" href="%s">%s</a>', esc_url( get_audiotheme_venue_admin_url() ), esc_html( $post_type_object->labels->add_new ) );
	?></h2>
	
	<?php
	if ( isset( $_REQUEST['deleted'] ) || isset( $_REQUEST['message'] ) || isset( $_REQUEST['updated'] ) ) {
		$notices = array();
		?>
		<div id="message" class="updated">
			<p>
				<?php
				$messages = array(
					1 => __( 'Venue added.', 'audiotheme' )
				);
				
				if ( ! empty( $_REQUEST['message'] ) && isset( $messages[ $_REQUEST['message'] ] ) ) {
					$notices[] = $messages[ $_GET['message'] ];
				}
				
				if ( isset( $_REQUEST['updated'] ) && (int) $_REQUEST['updated'] ) {
					$notices[] = sprintf( _n( '%s venue updated.', '%s venues updated.', $_REQUEST['updated'] ), number_format_i18n( $_REQUEST['updated'] ) );
					unset( $_REQUEST['updated'] );
				}
				
				if ( isset( $_REQUEST['deleted'] ) && (int) $_REQUEST['deleted'] ) {
					$notices[] = sprintf( _n( 'Venue permanently deleted.', '%s venues permanently deleted.', $_REQUEST['deleted'] ), number_format_i18n( $_REQUEST['deleted'] ) );
					unset( $_REQUEST['deleted'] );
				}
				
				if ( $notices )
					echo join( ' ', $notices );
				unset( $notices );
				
				$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'deleted', 'message', 'updated' ), $_SERVER['REQUEST_URI'] );
				?>
			</p>
		</div>
	<?php } ?>
	

	<form action="" method="get">
		<input type="hidden" name="page" value="venues">
		<?php $venues_list_table->search_box( $post_type_object->labels->search_items, $post_type_object->name ); ?>
		
		<?php $venues_list_table->display(); ?>
	</form>
	
</div><!--end div.wrap-->
<script type="text/javascript">
jQuery(function($) {
	var $state = $('#venue-state'),
		$country = $('#venue-country');
	
	
	$('#venue-city').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: 'http://ws.geonames.org/searchJSON',
				data: {
					featureClass: 'P',
					style: 'full',
					maxRows: 12,
					name_startsWith: request.term
				},
				dataType: 'JSONP',
				success: function( data ) {
					response( $.map( data.geonames, function( item ) {
						return {
							label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
							value: item.name,
							adminCode: item.adminCode1,
							countryName: item.countryName,
							timezone: item.timezone.timeZoneId
						}
					}));
					console.log(data);
				}
			});
		},
		minLength: 2,
		select: function(e, ui) {
			if ('' == $state.val())
				$state.val(ui.item.adminCode);
			
			if ('' == $country.val())
				$country.val(ui.item.countryName);
		}
	});
});
</script>
<style type="text/css">
.form-wrap .form-field { margin: 0; padding-bottom: 0;}
p.search-box { margin-bottom: 8px;}

.column-website { width: 4em;}
.column-website a span.audiotheme-column-icon { width: 16px; text-indent: -9999px; background: url("<?php echo AUDIOTHEME_URI . 'admin/images/link.png'; ?>") 0 0 no-repeat;}
.fixed .column-gigs { width: 5em; text-align: center;}
.venue-website-link img { }
</style>