<?php
_nl('<b>' . get_the_title() . '</b>', 1);
$auth = cs_work_get('author');
if ($auth != null) echo 'by ' . CHtml::link($auth->name, WorkNav::author($auth));
?>
			<div class="find">
				<form method="get" class="searchform" action="<?php echo WorkNav::post($id, 'search'); ?>">
					<div>
						<input type="hidden" name="post_type" value="work" />
						<input type="hidden" name="p" value="<?php echo $id; ?>" />
						<input type="hidden" name="search" value="1" />
						<label class="screen-reader-text" for="s"><?php _e('Find: ', 'desaindigital') ?></label>
						<input value="<?php echo esc_attr( get_search_query()); ?>" name="s" class="s" type="text"/>
						<input class="searchsubmit" value="<?php esc_attr_e( 'Search', 'desaindigital'); ?>" type="submit"/>
					</div>
				</form>
			</div><!-- End .find -->
