<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Search controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Search Controller  
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Search_Controller extends Main_Controller {
	
	function __construct()
    {
        parent::__construct();	
    }
	
	
	/**
  	 * Build a search query with relevancy
	 * Stop word control included
  	 */
    public function index($page = 1) 
	{
		$this->template->content = new View('search');	
		
		$search_query = "";
		$keyword_string = "";
		$where_string = "";
		$plus = "";
		$or = "";
		$search_info = "";
		$html = "";
		$pagination = "";
		
		// Stop words that we won't search for
		// Add words as needed!!
		$stop_words = array('the', 'and', 'a', 'to', 'of', 'in', 'i', 'is', 'that', 'it', 
		'on', 'you', 'this', 'for', 'but', 'with', 'are', 'have', 'be', 
		'at', 'or', 'as', 'was', 'so', 'if', 'out', 'not');
		
		if ($_GET)
		{
			if (isset($_GET['k']))
			{
				$keyword_raw = $_GET['k'];
			}
			else
			{
				$keyword_raw = "";
			}
		}
		else
		{
			$keyword_raw = "";
		}
		
		$keywords = explode(' ', $keyword_raw);
		if (is_array($keywords) && !empty($keywords)) {
			array_change_key_case($keywords, CASE_LOWER);
			$i = 0;
			foreach($keywords as $value) {
				if (!in_array($value,$stop_words) && !empty($value))
				{
					$chunk = mysql_real_escape_string($value);
					if ($i > 0) {
						$plus = ' + ';
						$or = ' OR ';
					}
					// Give relevancy weighting
					// Title weight = 2
					// Description weight = 1
					$keyword_string = $keyword_string.$plus."(CASE WHEN incident.incident_title LIKE '%$chunk%' THEN 3 ELSE 0 END) + (CASE WHEN incident.incident_description LIKE '%$chunk%' THEN 2 ELSE 0 END) + ( CASE WHEN location.location_name LIKE  '%$chunk%' THEN 1 ELSE 0 END )";
					$where_string = $where_string.$or."incident.incident_title LIKE '%$chunk%' OR incident.incident_description LIKE '%$chunk%' OR location.location_name LIKE '%$chunk%' ";
					$i++;
				}
			}
			if (!empty($keyword_string) && !empty($where_string))
			{
                            $search_query = "SELECT DISTINCT incident.* ,location.id AS loc_id, location.location_name,location.location_visible, (".$keyword_string.") AS relevance FROM incident INNER JOIN location ON incident.location_id = location.id WHERE (".$where_string.") ORDER BY relevance DESC LIMIT ";
			}
		}
		
		if (!empty($search_query))
                {
                                            
			// Pagination
			$pagination = new Pagination(array(
				'query_string'    => 'page',
				'items_per_page' => (int) Kohana::config('settings.items_per_page'),
				'total_items'    => ORM::factory('incident')->join('location','incident.location_id','location.id','LEFT')->where($where_string)->count_all()
			));
		       
			$db = new Database();
			$query = $db->query($search_query . $pagination->sql_offset . ",". (int)Kohana::config('settings.items_per_page'));
			
			// Results Bar
			if ($pagination->total_items != 0)
			{			
				$search_info .= "<div class=\"search_info\">";
				$search_info .= "Showing results " . ( $pagination->sql_offset + 1 ). " to " . ( (int) Kohana::config('settings.items_per_page') + $pagination->sql_offset ) . " of about " . $pagination->total_items . " for <strong>" . $keyword_raw . "</strong>";
				$search_info .= "</div>";
			} else { 
				$search_info .= "<div class=\"search_info\">0 Results</div>";
				$html .=	"<div class=\"search_result\">";
				$html .= 	"<h3>Your search \"<strong>" . $keyword_raw . "</strong>\" did not match any documents.</h3>";
				$html .=	"</div>";
				$pagination = "";
                        }
                        
			foreach ($query as $search)
	        {
				$incident_id = $search->id;
				$incident_title = $search->incident_title;
					$highlight_title = "";
					$incident_title_arr = explode(' ', $incident_title); 
					foreach($incident_title_arr as $value) {
						if (in_array(strtolower($value),$keywords) && !in_array(strtolower($value),$stop_words))
						{
							$highlight_title .= "<span class=\"search_highlight\">" . $value . "</span> ";
						}
						else
						{
							$highlight_title .= $value . " ";
						}
					}
				$incident_description = $search->incident_description;
					// Trim to 180 characters without cutting words
					if ((strlen($incident_description) > 180) && (strlen($incident_description) > 1)) {
						$whitespaceposition = strpos($incident_description," ",175)-1;
						$incident_description = substr($incident_description, 0, $whitespaceposition);
					}
					$highlight_description = "";
					$incident_description_arr = explode(' ', $incident_description); 
					foreach($incident_description_arr as $value) {
						if (in_array(strtolower($value),$keywords) && !in_array(strtolower($value),$stop_words))
						{
							$highlight_description .= "<span class=\"search_highlight\">" . $value . "</span> ";
						}
						else
						{
							$highlight_description .= $value . " ";
						}
					}
				$incident_date = date('D M j Y g:i:s a', strtotime($search->incident_date));
				
				$html .=	"<div class=\"search_result\">";
	            $html .=	"<h3><a href=\"" . url::base() . "reports/view/" . $incident_id . "\">" . $highlight_title . "</a></h3>";
	            $html .=	$highlight_description . " ...";
				$html .=	"<div class=\"search_date\">" . $incident_date . " | Relevance: <strong>+" . $search->relevance . "</strong></div>";
				$html .=	"</div>";
			}
		}
		else
		{
			// Results Bar
			$search_info .= "<div class=\"search_info\">0 Results</div>";
			$html .=	"<div class=\"search_result\">";
			$html .= 	"<h3>Your search \"<strong>" . $keyword_raw . "</strong>\" did not match any documents.</h3>";
			$html .=	"</div>";
		}
		$html .= $pagination;
		
		$this->template->content->search_info = $search_info;
		$this->template->content->search_results = $html;
		
	}
	
}
