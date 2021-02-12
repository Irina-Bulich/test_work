<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since 1.0.0
 */
// this page edit

get_header();
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );			
$slag_term = $term->slug; // отобразит slug
$description = get_the_archive_description();
?>

	<header class="page-header alignwide">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?><span>(<?php echo  $term->count; ?>)</span>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header><!-- .page-header -->

			<section class="events">
			<div class="container">
				<div class="sitebar">
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
						'posts_per_page' => '12',			
						'showposts' => $showposts,
						'caller_get_posts' => $do_not_show_stickies,
						'paged'	 => $paged,
						'posts_per_archive_page' => '12',
						'include_children' => true,
						'show_all'     => false, // показаны все страницы участвующие в пагинации
						'end_size'     => 1,     // количество страниц на концах
						'mid_size'     => 1,     // количество страниц вокруг текущей
						'prev_next'    => true,  // выводить ли боковые ссылки "предыдущая/следующая страница".
						'prev_text'    => __('>>'),
						'next_text'    => __('<<'),
						'add_args'     => false, // Массив аргументов (переменных запроса), которые нужно добавить к ссылкам.
						'add_fragment' => '',     // Текст который добавиться ко всем ссылкам.
						'screen_reader_text' => __('Posts navigation'),
						'tax_query' => array(
							array(
								'taxonomy' => 'events_tags',
								'field' => 'slug',
								'terms' => $slag_term,
							)
						),
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
				$events_month = [];					
				 ?>	
					<?php if ($my_query->have_posts()) { ?>
					<div class="events-list">
						
					<?php while ($my_query->have_posts()) : $my_query->the_post();
						
						$events_start = get_post_meta( $post->ID, 'events_start', true);
						if( !empty( $events_start ) ) :										
							$arr = explode(" ", $events_start);
							$arr_part_1 = explode("-", $arr[0]);							
						endif;	?>
						<?php $events_month_view = in_array($arr_part_1[1] , $events_month); ?>
						<?php if (!$events_month_view) : ?>
						<?php array_push($events_month, $arr_part_1[1]);?>
						<div class="events-list__item">
						<div class="monthe-event">
							<?php 	$monthes = array("Янвать","Февраль","Март","Апрель","Май","Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
									$arr_part_1 = explode("-", $arr[0]);					
									$month_release = (int) $arr_part_1[1] - 1;
							?>
							<h5><?php echo $monthes[$month_release] .  ' ' . $arr_part_1[0]; ?></h5>
						</div>
							<a href="<?php the_permalink($post->ID); ?>">
								<div>
									<img src="<?= get_the_post_thumbnail_url($post->ID); ?>" alt="image posts" class="events-list__item-img">
									<h4><?php  echo the_title(); ?>			
									</h4>
															
								<div class="block-date-row">	
									<?php 										
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
					<?php endif; ?>
				<?php endwhile; ?>						
					</div>
<!-- 			end -->
			<?php } else { get_template_part( 'template-parts/content/content-none' ); } ?>
				<?php wp_reset_postdata();  ?>
			</div>
			</div>
		</section>	

<?php get_footer(); ?>
