<?php
_nl('<b>' . get_the_title() . '</b>' , 1);
$auth = cs_work_get('author');
if ($auth != null) _nl('by ' . WorkNav::termLink($auth), 1);
_nl('<b>Type</b>: ' . WorkNav::typeLink(cs_work_read($id, 'type')));
$sub = cs_work_get('subtype');
if ($sub) _nl(' -> ' . $sub, 1); else _nl('', 1);
?>
			<div class="find work">
				<form method="get" class="searchform" action="<?php echo WorkNav::post($id, 'search'); ?>">
					<div>
						<label class="screen-reader-text" for="s"><?php _e('Find: ', 'desaindigital') ?></label>
						<input value="<?php echo esc_attr( get_search_query()); ?>" name="s" class="s" type="text"/>
						<input class="searchsubmit" value="<?php esc_attr_e( 'Search', 'desaindigital'); ?>" type="submit"/>
					</div>
				</form>
			</div><!-- End .find -->
