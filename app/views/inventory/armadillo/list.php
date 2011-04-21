<?php

	global $wp_rewrite;

	$parameters = $this->parameters;

	$args = array(
		'base' => @add_query_arg( 'page' , '%#%' ),
		'current' => $inventory[ 0 ]->pagination->on_page,
		'total' => $inventory[ 0 ]->pagination->total,
		'next_text' => __( 'Next &raquo;' ),
		'prev_text' => __( '&laquo; Previous' ),
		'show_all' => false,
		'type' => 'plain'
	); 

	$sale_class = isset( $parameters[ 'saleclass' ] ) ? ucwords( $parameters[ 'saleclass' ] ) : 'All';

?>

<div class="dealertrend inventory wrapper">
	<br class="clear" id="top" />
	<div class="listing wrapper">
		<?php echo $breadcrumbs; ?>
		<div class="pager">
			<?php echo paginate_links( $args ); ?>
		</div>
		<div class="sidebar">
			<div class="total-found"><?php echo !empty( $inventory ) ? $inventory[0]->pagination->total * $inventory[0]->pagination->per_page : 0; ?> Cars Found</div>
			<div class="quick-links">
				<h3>Refine Your Search</h3>
				<ul>
					<li class="expanded">
						<span>Body Style</span>
						<ul>
							<li><a href="<?php echo @add_query_arg( array( 'vehicleclass' => 'car' , 'page=1' ) ); ?>">Car</a></li>
							<li><a href="<?php echo @add_query_arg( array( 'vehicleclass' => 'truck' , 'page=1' ) ); ?>">Truck</a></li>
							<li><a href="<?php echo @add_query_arg( array( 'vehicleclass' => 'suv' , 'page=1' ) ); ?>">SUV</a></li>
							<li><a href="<?php echo @add_query_arg( array( 'vehicleclass' => 'van' , 'page=1' ) ); ?>">Van</a></li>
						</ul>
					</li>
					<li class="expanded">
						<span>Make</span>
						<ul>
							<?php
								foreach( $vehicle_management_system->get_makes( array( 'saleclass' => $sale_class ) ) as $make ) {
									if( !empty( $wp_rewrite->rules ) ) {
										echo '<li><a href="/inventory/' . $sale_class . '/' . $make . '/">' . $make . '</a></li>';
									} else {
										echo '<li><a href="' . @add_query_arg( array( 'make' => $make , 'page=1' ) ) . '">' . $make . '</a></li>';
									}
								}
							?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="content">
			<div class="sort">
				<div class="column">Sort by</div>
				<?php
					$sort = isset( $_GET[ 'sort' ] ) ? $_GET[ 'sort' ] : NULL;
					switch( $sort ) {
						case 'year_asc': $sort_year_class = 'asc'; break;
						case 'year_desc': $sort_year_class = 'desc'; break;
						case 'price_asc': $sort_price_class = 'asc'; break;
						case 'price_desc': $sort_price_class = 'desc'; break;
						case 'mileage_asc': $sort_mileage_class = 'asc'; break;
						case 'mileage_desc': $sort_mileage_class = 'desc'; break;
						default: $sort_year_class = $sort_price_class = $sort_mileage_class = null; break;
					}
					$sort_year = $sort != 'year_asc' ? 'year_asc' : 'year_desc';
					$sort_mileage = $sort != 'mileage_asc' ? 'mileage_asc' : 'mileage_desc';
					$sort_price = $sort != 'price_asc' ? 'price_asc' : 'price_desc';
				?>
				<div><a class="<?php echo $sort_year_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_year , 'page=1' ) ); ?>">Year</a></div>
				<div><a class="<?php echo $sort_price_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_price , 'page=1' ) ); ?>">Price</a></div>
				<div class="last"><a class="<?php echo $sort_mileage_class; ?>" href="<?php echo @add_query_arg( array( 'sort' => $sort_mileage , 'page=1' ) ); ?>">Mileage</a></div>
			</div>
			<div class="items">
				<?php
					if( empty( $inventory ) ) {
						echo '<h2><strong>Unable to find inventory items that matched your search criteria.</strong></h2>';
					} else {
						foreach( $inventory as $inventory_item ):
							$year = $inventory_item->year;
							$make = $inventory_item->make;
							$model = urldecode( $inventory_item->model_name );
							$vin = $inventory_item->vin;
							$trim = urldecode( $inventory_item->trim );
							$engine = $inventory_item->engine;
							$transmission = $inventory_item->transmission;
							$exterior_color = $inventory_item->exterior_color;
							$interior_color = $inventory_item->interior_color;
							setlocale(LC_MONETARY, 'en_US');
							$prices = $inventory_item->prices;
							$asking_price = money_format( '%(#0n', $prices->asking_price );
							$display_price = $prices->asking_price > 0 ? 'Price:' . $asking_price : $prices->default_price_text;
							$stock_number = $inventory_item->stock_number;
							$odometer = $inventory_item->odometer;
							$icons = $inventory_item->icons;
							$thumbnail = urldecode( $inventory_item->photos[ 0 ]->small );
							if( !empty( $wp_rewrite->rules ) ) {
								$inventory_url = '/inventory/' . $sale_class . '/' . $make . '/' . $model . '/' . $state . '/' . $city . '/'. $vin . '/';
							} else {
								$inventory_url = '?taxonomy=inventory&amp;saleclass=' . $sale_class . '&amp;make=' . $make . '&amp;model=' . $model . '&amp;state=' . $state . '&amp;city=' . $city . '&amp;vin='. $vin;
							}
							$generic_vehicle_title = $year . ' ' . $make . ' ' . $model; ?>
							<div class="item" id="<?php echo $vin; ?>">
								<div class="photo">
									<a href="<?php echo $inventory_url; ?>" title="<?php echo $generic_vehicle_title; ?>">
										<img src="<?php echo $thumbnail; ?>" alt="<?php echo $generic_vehicle_title; ?>" title="<?php echo $generic_vehicle_title; ?>" />
									</a>
								</div>
								<div class="main-line">
									<a href="<?php echo $inventory_url; ?>" title="<?php echo $generic_vehicle_title; ?>" class="details">
										<span class="year"><?php echo $year; ?></span>
										<span class="make"><?php echo $make; ?></span>
										<span class="model"><?php echo $model; ?></span>
										<span class="trim"><?php echo $trim; ?></span>
									</a>
								</div>
								<div class="details-left">
									<span class="interior-color">Int. Color: <?php echo $interior_color; ?></span>
									<span class="exterior-color">Ext. Color: <?php echo $exterior_color; ?></span>
									<span class="transmission">Trans: <?php echo $transmission; ?></span>
								</div>
								<div class="details-right">
									<span class="stock-number">Stock #: <?php echo $stock_number; ?></span>
									<span class="odometer">Mileage: <?php echo $odometer; ?></span>
									<span class="vin">VIN: <?php echo $vin; ?></span>
								</div>
								<div class="icons">
									<?php echo $icons; ?>
								</div>
								<div class="price">
									<?php echo $display_price; ?>
									<a href="<?php echo $inventory_url; ?>" title="More Information: <?php echo $generic_vehicle_title; ?>">More Information</a>
								</div>
								<br class="clear" />
							</div>
						<?php
						flush();
						endforeach;
					}
				?>
			</div>
		</div>
	</div>
	<?php echo $breadcrumbs; ?>
	<div class="pager">
		<?php echo paginate_links( $args ); ?>
	</div>
	<a href="#top" title="Return to Top" class="return-to-top">Return to Top</a>
</div>