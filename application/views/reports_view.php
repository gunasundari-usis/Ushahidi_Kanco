<?php 
/**
 * Reports view page.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     API Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
		<div id="content">
		  <div class="content-bg">
		    <!-- start incident block -->
		    <div class="big-block">
		      <div class="big-block-top">
		        <div class="big-block-bottom">
		          <div class="incident-name">
		            <h1><?php echo $incident_title; ?></h1>
		            <ul>
		              <li>
		                <strong>LOCATION</strong>
		                <p><?php echo $incident_location; ?></p>
		              </li>
		              <li>
		                <strong>DATE</strong>
		                <p><?php echo $incident_date; ?></p>
		              </li>
		              <li>
		                <strong>TIME</strong>
		                <p><?php echo $incident_time; ?></p>
		              </li>
		              <li>
		                <strong>CATEGORY</strong>
                        <?php
                        foreach($incident_category as $category) 
                        { 
                            echo "<a href=\"".url::base()."reports/?c=".$category->category->id."\">" .
                                $category->category->category_title . "</a>&nbsp;&nbsp;&nbsp;";
                        }
                        ?>
                              </li>
                        <!-- custom fields display -->
                        <?php
                        if( is_array($disp_custom_fields) ) {
                            foreach ($disp_custom_fields as $field_id => $field_property)
			    {
                                echo  "<li><strong>".$form_field_names[$field_id]['field_name']."</strong>";
                                //This is a hack to check if field name is labelled "Website" then make link clickable. It needs to be properly handled.
                                
                                if( $form_field_names[$field_id]['field_name'] == "Website" ) {
                                    echo '<p><a href="http://'.$field_property.'">'.$field_property.'</a></p>';
                                }else {
                                    echo "<p>".$field_property."</p></li>";
                                }
                            }
                        }
                        ?>

		              <li>
		                <strong>VERIFIED</strong>
                        <?php        
                        if ( $incident_verified == 1 )
                        {
                            echo "<p><strong class=\"green\">YES</strong></p>";
                        }
                        else
                        {
                            echo "<p><strong class=\"red\">NO</strong></p>";
                        }
                        ?>
		              </li>
		            </ul>
		          </div>
		          <div class="incident-map">
		            <ul class="legend">
		              <li class="ico-red">INCIDENT</li>
                              <li class="ico-orange">NEARBY INCIDENTS</li>
                              <li><a href="#" onclick="window.print();return false;"><img src="/media/img/printButton.png" alt="Print this page"  title="Print this page" /></a>
                              </li>
		            </ul>
		            <div class="map-holder" id="map"></div>
		          </div>
		          <div class="report-description">
		            <div class="title">
		              <h2>Organization Profile Description</h2>
		              <a href="#comments"><span>+ add information</span></a>
		            </div>
		            <div class="orig-report">
		              <div class="report">
		                <h4>Original Organization Profile</h4>
		                <p><?php echo $incident_description; ?></p>
						<div class="report_rating">
							<div>
							Credibility:&nbsp;
							<a href="javascript:rating('<?php echo $incident_id; ?>','add','original','oloader_<?php echo $incident_id; ?>')"><img id="oup_<?php echo $incident_id; ?>" src="<?php echo url::base() . 'media/img/'; ?>up.png" alt="UP" title="UP" border="0" /></a>&nbsp;
							<a href="javascript:rating('<?php echo $incident_id; ?>','subtract','original')"><img id="odown_<?php echo $incident_id; ?>" src="<?php echo url::base() . 'media/img/'; ?>down.png" alt="DOWN" title="DOWN" border="0" /></a>&nbsp;
							</div>
							<div class="rating_value" id="orating_<?php echo $incident_id; ?>"><?php echo $incident_rating; ?></div>
							<div id="oloader_<?php echo $incident_id; ?>" class="rating_loading" ></div>
						</div>
		              </div>
		            </div>
		            <div class="orig-report">
		              <div class="discussion">
		                <h5>ADDITIONAL PROFILE INFORMATION&nbsp;&nbsp;&nbsp;(<a href="#comments">Add</a>)</h5>
                        <?php
                        	foreach($incident_comments as $comment)
			                {
                                echo "<div class=\"discussion-box\">";
                                echo "<p><strong>" . $comment->comment_author . "</strong>&nbsp;(" . date('M j Y', strtotime($comment->comment_date)) . ")</p>";
                                echo "<p>" . $comment->comment_description . "</p>";
                                echo "<div class=\"report_rating\">";
                                echo "	<div>";
                                echo "	Credibility:&nbsp;";
                                echo "	<a href=\"javascript:rating('" . $comment->id . "','add','comment','cloader_" . $comment->id . "')\"><img id=\"cup_" . $comment->id . "\" src=\"" . url::base() . 'media/img/' . "up.png\" alt=\"UP\" title=\"UP\" border=\"0\" /></a>&nbsp;";
                                echo "	<a href=\"javascript:rating('" . $comment->id . "','subtract','comment','cloader_" . $comment->id . "')\"><img id=\"cdown_" . $comment->id . "\" src=\"" . url::base() . 'media/img/' . "down.png\" alt=\"DOWN\" title=\"DOWN\" border=\"0\" /></a>&nbsp;";
                                echo "	</div>";
                                echo "	<div class=\"rating_value\" id=\"crating_" . $comment->id . "\">" . $comment->comment_rating . "</div>";
                                echo "	<div id=\"cloader_" . $comment->id . "\" class=\"rating_loading\" ></div>";
                                echo "</div>";
                                echo "</div>";
			                }
                        ?>
		              </div>
		            </div>		
		          </div>
		        </div>
		      </div>
		    </div>
		    <!-- end incident block <> start other report -->

		 	<div class="blocks-holder">
				<?php
				if( count($incident_photos) > 0 ) 
				{
				?>
					<!-- start images -->
					<div class="small-block images">
						<h3>Images</h3>
						<div class="block-bg">
							<div class="block-top">
								<div class="block-bottom">
									<div class="photoslider" id="default"></div>
								</div>
							</div>
						</div>
					</div>
					<!-- end images <> start side block -->
				<?php 
				} else {
				?> 
					<!-- start mainstream news of incident -->	
					<div class="small-block images">
						<h3>Related Organization News</h3>
						<div class="block-bg">
							<div class="block-top">
								<div class="block-bottom">
									<ul>
										<li>
											<ul class="title">
												<li class="w-01">TITLE</li>
												<li class="w-02">SOURCE</li>
												<li class="w-03">DATE</li>
											</ul>
										</li>
										<?php
										foreach ($feeds as $feed)
										{
											$feed_id = $feed->id;
											$feed_title = text::limit_chars($feed->item_title, 40, '...', True);
											$feed_link = $feed->item_link;
											$feed_date = date('M j Y', strtotime($feed->item_date));
											$feed_source = text::limit_chars($feed->feed->feed_name, 15, "...");
											?>
											<li>
												<ul>
													<li class="w-01">
													<a href="<?php echo $feed_link; ?>" target="_blank">
													<?php echo $feed_title ?></a></li>
													<li class="w-02"><?php echo $feed_source; ?></li>
													<li class="w-03"><?php echo $feed_date; ?></li>
												</ul>
											</li>
											<?php
										}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- end mainstream news of incident -->
				<?php
				}?>
			
				<div class="side-block">
					<div class="small-block">
						<h3>Profile(s)</h3>
						<div class="block-bg">
							<div class="block-top">
								<div class="block-bottom">
									<ul>
										<li>
											<ul class="title">
												<li class="w-01">TITLE</li>
												<li class="w-02">LOCATION</li>
												<li class="w-03">DATE</li>
											</ul>
										</li>
										<?php
										foreach($incident_neighbors as $neighbor)
										{
											echo "<li>";
											echo "<ul>";
											echo "<li class=\"w-01\"><a href=\"" . url::base(); 
											echo "reports/view/" . $neighbor->id . "\">" . $neighbor->incident_title . "</a></li>";
											echo "<li class=\"w-02\">" . $neighbor->location->location_name . "</li>";
											echo "<li class=\"w-03\">" . date('M j Y', strtotime($neighbor->incident_date)) . "</li>";
											echo "</ul>";
											echo "</li>";
										}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<?php 
				if( $incident_photos <= 0) 
				{
				?> 
					<div class="small-block">
						<h3>Related Mainstream News of Incident</h3>
						<div class="block-bg">
							<div class="block-top">
								<div class="block-bottom">
									<ul>
										<li>
											<ul class="title">
												<li class="w-01">TITLE</li>
												<li class="w-02">SOURCE</li>
												<li class="w-03">DATE</li>
											</ul>
										</li>
										<?php
										foreach ($feeds as $feed)
										{
											$feed_id = $feed->id;
											$feed_title = text::limit_chars($feed->item_title, 40, '...', True);
											$feed_link = $feed->item_link;
											$feed_date = date('M j Y', strtotime($feed->item_date));
											$feed_source = text::limit_chars($feed->feed->feed_name, 15, "...");
											?>
											<li>
											<ul>
												<li class="w-01">
												<a href="<?php echo $feed_link; ?>" target="_blank">
												<?php echo $feed_title ?></a></li>
												<li class="w-02"><?php echo $feed_source; ?></li>
												<li class="w-03"><?php echo $feed_date; ?></li>
											</ul>
											</li>
											<?php
										}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php }  ?>
				<!-- end side block -->
			
			
				<!-- start videos -->
				<?php
				if( count($incident_videos) > 0 ) 
				{
				?>
					<div class="small-block images">
						<h3>Videos</h3>
						<div class="block-bg">
							<div class="block-top">
								<div class="block-bottom">

									<div class="slider-wrap">
										<div id="slider1" class="csw">
											<div class="panelContainer">

												<?php
												// embed the video codes
												foreach( $incident_videos as $incident_video) {
												?>
													<div class="panel">
														<div class="wrapper">
															<p>
															<?php
															$videos_embed->embed($incident_video,'');
															?>	
															<p>
														</div>
													</div>
												<?php } ?>

											</div><!-- .panelContainer -->
										</div><!-- #slider1 -->
									</div><!-- .slider-wrap -->

								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<!-- end incident block <> start other report -->
			
			</div>
			
			
			
			<br />
		    <!-- end incident block <> start other report -->
			<a name="comments"></a>
			<div class="big-block">
				<div class="big-block-top">
				<div class="big-block-bottom">
					<div id="comments" class="report_comment">
						<?php
						if ($form_error) {
						?>
							<!-- red-box -->
							<div class="red-box">
								<h3>Error!</h3>
								<ul>
								<?php
								foreach ($errors as $error_item => $error_description)
								{
									print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
								}
								?>
								</ul>
							</div>
						<?php
						}
						?>
						<?php print form::open(NULL, array('id' => 'commentForm', 'name' => 'commentForm')); ?>
						<div class="report_row">
                        	<strong>Name:</strong><br />
							<?php print form::input('comment_author', $form['comment_author'], ' class="text"'); ?>
                        </div>
						<div class="report_row">
                        	<strong>E-Mail:</strong><br />
							<?php print form::input('comment_email', $form['comment_email'], ' class="text"'); ?>
                        </div>
						<div class="report_row">
							<strong>Comments:</strong><br />
							<?php print form::textarea('comment_description', $form['comment_description'], ' rows="4" cols="40" class="textarea long" ') ?>
                        </div>
						<div class="report_row">
                                                        <strong>Security Code:</strong><br />
                                                        <p><?php echo Kohana::lang('ui_main.captcha');?></p>
							<?php print $captcha->render(); ?><br />
							<?php print form::input('captcha', $form['captcha'], ' class="text"'); ?>
                        </div>
                        <div class="report_row">
                        	<input name="submit" type="submit" value="Submit Comment" class="btn_blue" />
                        </div>
						<?php print form::close(); ?>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		</div>
