<?php 
/**
 * Search view page.
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
		<!-- start search block -->
		<div class="big-block">
			<div class="big-block-top">
				<div class="big-block-bottom">
					<h1>Search Results</h1>
                                        <div class="search_block">
                                                <div style="text-align:right;">
                                                    <a href="#" onclick="window.print();return false;"><img src="/media/img/printButton.png" alt="Print this page"  title="Print this page" /></a>
                                                </div>
						<?php echo $search_info; ?>
						<?php echo $search_results; ?>
					</div>
				</div>
			</div>
		</div>
		<!-- end search block -->
	</div>
</div>
