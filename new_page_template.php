<?php /* Template Name: New page template */ ?>

<?php get_header(); ?>
<body>
	<main>
		<section class="events">
			<div class="container">
				<div class="sitebar">
					<h1>
						Все мероприятия
					</h1>
				<div class="filter-field__gender-release">		
							<?php  if( $terms = get_terms( 'events_tags' ) ) : 
								echo '<ul class="audience-list">';
									foreach ($terms as $term) :
									 echo '<li><a href="/events_tags/' .  $term->slug  . '/">' .  $term->name . '<span>(' .  $term->count . ')</span></a></li>';					 
									endforeach;
								echo '</ul>';
						endif; ?>
					</div>		
				</div>
				<div class="events-row">
					
					<?php
					$date_now = date('Y-m-d');
					$showposts = 0;
					$do_not_show_stickies = 1;
					$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
					$args = array(
						'post_type' => "events",
						'post_status' => array( 'publish' ),
						'showposts' => $showposts,
						'caller_get_posts' => $do_not_show_stickies,
						'paged'	 => $paged,
						'posts_per_archive_page' => '12',
						'include_children' => true,
 						'meta_query' => array(
                            'relation' => 'AND',
                             array(
                     			'key' => 'events_finish',
                     			'value' =>  $date_now,
                     			'type' => 'date',
                    			    'compare' => '>',
                 			),
 						),
 						'orderby' => 'meta_value',
						'meta_key' => 'events_start',
						'order'   => 'ASC',
 				);
					$my_query = new WP_Query($args);
				 ?>	
					<?php if ($my_query->have_posts()) { ?>
					<div class="events-list">
						
					<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
						<div class="events-list__item">
							<a href="<?php the_permalink($post->ID); ?>">
								<div>
									<img src="<?= get_the_post_thumbnail_url($post->ID); ?>" alt="image posts" class="events-list__item-img">
									<h4><?php  echo the_title(); ?>					
									</h4>
															
								<div class="block-date-row">	
									<?php 
										$events_start = get_post_meta( $post->ID, 'events_start', true);
											if( !empty( $events_start ) ) {								
												echo veiw_date($events_start);
											}
									?> - 
									<?php 
										$events_finish = get_post_meta( $post->ID, 'events_finish', true);
											if( !empty($events_finish) ) {								
												echo veiw_date($events_finish);
											}
									?>
								</div>
							<div class="block-description-row">	
								<div class="block-description-title">
									Для кого:
								</div>
								<div class="block-description-value">
									<span class="stylecode">
										<?php	$audience_taxs = get_the_terms( $post->ID, 'events_tags' );
														$audience = [];
														foreach( $audience_taxs as $audience_tax ):									
														array_push( $audience, $audience_tax->name);
														endforeach;
														$audience = implode(", ", $audience);
														echo $audience; ?>
									</span>
										</div>
									</div>
								</div>
							</a>
						</div>
				<?php endwhile; ?>						
					</div>
<!-- 			end -->
			<?php } else { get_template_part( 'template-parts/content/content-none' ); } ?>
				<?php wp_reset_postdata();  ?>
			</div>
			</div>
		</section>	
	</main>
    <?php get_footer(); ?>

</body>

</html>