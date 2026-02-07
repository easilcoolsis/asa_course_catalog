<?php

namespace AlphastarCourseCatalog\templates;
use AlphastarCourseCatalog\AsaCourseCatalogTemplate;

//Defaults
$reg_button_text		= !isset($reg_button_text) ? __('SCHEDULE', 'event_espresso') : $reg_button_text;
$subject_filter_text	= !isset($subject_filter_text) ? __('Subject', 'event_espresso') : $subject_filter_text;
$level_filter_text		= !isset($level_filter_text) ? __('Level', 'event_espresso') : $level_filter_text;
$courseCatalogTemplate = new AsaCourseCatalogTemplate();

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if(!empty($queries)) { 
	$subject =  isset($queries['subject']) ? $queries['subject'] : "";
	$posted_level = isset($queries['level']) ? $queries['level'] : "";
	$searchfilter = isset($queries['searchfilter']) ? $queries['searchfilter'] : "";
}

$searchfilter = !isset($searchfilter) ? '' :  $searchfilter;
$subject =  !isset($subject) ? '' :  $subject;
$posted_level =  !isset($posted_level) ? '' :  $posted_level;

	?>
<br><br>

<form method="post" action="">'
  <div class="row" >
	<div class="col-md-3">
		<label><?php echo $subject_filter_text; ?></label>
		<p class="subject-filter">
			<select style="border-radius: 20px;border-color: #007bff;" id="ee_filter_subject" name='subject'>
			<option value="" class="ee_filter_show_all"><?php echo __('Show All', 'event_espresso'); ?></option>
			<?php
				$ee_subjects = array('Mathematics','Computer Science',  'AI', 'Physics' );
				foreach($ee_subjects as $sbj) {
					$subject_code = $courseCatalogTemplate->getSubjectCode($sbj);
					if (strtolower($subject_code) === strtolower($subject))
						echo '<option  value="' . $subject_code . '" selected="'.$subject_code.'" class="' . $subject_code . '">'. $sbj. '</option>';
					else
					echo '<option value="' . $subject_code . '" class="' . $subject_code . '">'. $sbj. '</option>';	
				}

			?>
			</select>
		</p>
	</div>
   <div class="col-md-3">
		<label><?php echo $level_filter_text; ?></label>
		<p class="level-filter">
			<select style="border-radius: 20px;border-color: #007bff;" id="ee_filter_level" name='level'>
			<option value="" class="ee_filter_show_all"><?php echo __('Show All', 'event_espresso'); ?></option>
			<?php
				$ee_levels =  array('Elementary School', 'AMC 8/MathCounts', 'AMC 10/12', 'AIME', 'USA(J)MO', 'Programming', 'USACO Bronze',
				'USACO Silver', 'USACO Gold', 'USACO Platinum', 'Data Science', 'Machine Learning', 'Deep Learning', 'AI Olympiad', 'F=ma', 'Master' );
				foreach($ee_levels as $level) {

					$level_code =  $courseCatalogTemplate->get_level_code($level);
					if (strtolower($level_code) === strtolower($posted_level)) {
						echo '<option value="' . $level_code . '" selected="'.$level_code.'" class="' . $level_code . '">'. $level. '</option>';
					}
					else {
						echo '<option value="' . $level_code . '" class="' . $level_code . '">'. $level. '</option>';
					}
				}
			?>
			</select>
		</p>
	</div>
     <div class="col-md-3">

			<label><?php echo "Search Course"; ?></label>
		<p>
			<input style="border-radius: 20px;border-color: #007bff;" id="search-filter"  name='searchfilter' class="search-filter" type="text" value="<?php echo $searchfilter; ?>"/>
		</p>
		

     </div>
     <div class="col-md-2">

			<p>
			<a><input class="btn btn-primary" type="submit"  name="submit" id="filter-button" style="background-color:#007bff;border-radius:20px;width:100%;padding:15px;margin-top:13%;font-size:15px" target="_blank" value="Filter"/></a>
			</p>		

      </div>
	  
	</div>
	</form>
	<br>

	<table id="ee_filter_table"  class="espresso-table table footable" width="100%" data-page-size=25 data-filter="#filter">

		<thead class="espresso-table-header-row">
			<tr>
			<td><span class="self"></span></td>
			<td><span class="sortable"><a href="<?php echo $courseCatalogTemplate->sortorder('code'); ?>">Code<i class="fa fa-fw fa-sort"></i></a></span></td>
			<td><span class="sortable"><a href="<?php echo $courseCatalogTemplate->sortorder('course_name'); ?>">Course Name<i class="fa fa-fw fa-sort"></i></a></span></td>
			<td><span class="self">Subject</span></td>
			<td><span class="self">Level</span></td>
			<td><span class="self">Type</span></td>
			<td><span class="self">For Grades</span></td>
			<tr>
		</thead>
	
		<tfoot>
			<tr>
			<td colspan="3">
					<div class="pagination pagination-centered"></div>
				</td>
			</tr>
		</tfoot>

	<tbody>

	<?php

	$events = array();
	$orderBy = null;
	$orderBy = " order by JSON_VALUE(value,'$.code') asc";
	if(isset($_GET['order_by']) && isset($_GET['sort'])){
		if ($_GET['order_by'] == "course_name" )
		    $orderBy =  " order by JSON_VALUE(value,'$.name') ".$_GET['sort'];
		if ($_GET['order_by'] == "code" )
		    $orderBy =  " order by JSON_VALUE(value,'$.code') ".$_GET['sort'];
	}
	
	$events = $courseCatalogTemplate->getEvents($subject, $posted_level, strtolower($searchfilter), $orderBy);
	foreach($events as $index => $eventItem) {

		$course_code = $eventItem['code'];
		$course_name = $eventItem['name'];
		$subject_slugs = $eventItem['subject'];
		$level_slugs = $eventItem['level'];
		$type = $eventItem['type'];
		$for_grades = isset($eventItem['for_grades']) ? $eventItem['for_grades'] : '' ;

		switch ($subject_slugs) {
			case 'math':
				$course_link = 'mathematics';
				$subject = 'Mathematics';
				break;
			case 'cs':
				$course_link = 'computer-science';
				$subject = 'Computer Science';
				break;
			case 'physics':
				$course_link = 'physics';
				$subject = 'Physics';
				break;
			case 'ai':
					$course_link = 'ai';
					$subject = 'AI';
					break;
			default: 
				$course_link = '';
				break;	
		}

		$level =  $courseCatalogTemplate->get_level($level_slugs);

		//Create the event link
	    $button_text		= $reg_button_text;
		if(strpos(home_url(), "online") !== false) {
			$destination = "https://app.alphastar.online";
		} else {
		   $destination = "https://app.alphastar.academy";
		}
	 
		$registration_url = $destination . "/?searchfilter=".$course_code;

		//Create the register now button
		$live_button 		= '<span class="butonblue"> <a id="a_register_link-'.$course_code.'"  class="a_register_link" target="_blank" href="'.$registration_url.'"><button 
		style="background-color:#007bff" class="btn btn-primary"  >'.$button_text.'</button></a></span>';


		
		if(isset($course_code) && !empty($course_code)) {
		   $courses_url = home_url( '/' ) . 'course-detail/?COURSE='.$course_code;
		}
		else  {
		   $courses_url = home_url( '/' ) .$course_link;
		}
		$event_link = "<a class='a_event_link' href='".$courses_url ."' target = '_blank'>".$course_name."</a>";

        ?>
		<tr class="espresso-table-row <?php  echo " "; echo $subject_slugs; echo " "; echo $level_slugs;?>">

			<td class="td-group reg-col" nowrap="nowrap"><?php echo $live_button;?></td>
			<td class="event_title event-<?php echo $course_code; ?>"><?php echo $course_code?></td>
			<td class="event_title event-<?php echo $course_code; ?>"><?php echo $event_link?></td>
			<td class="event_subject event-<?php echo $course_code; ?>"><?php echo $subject?></td>			
			<td class="event_level event-<?php echo $course_code; ?>"><?php echo $level?></td>
			<td class="event_level event-<?php echo $course_code; ?>"><?php echo $type?></td>
			<td class="event_level event-<?php echo $course_code; ?>"><?php echo $for_grades?></td>

		</tr>
		<?php
	}
	echo '</table>';