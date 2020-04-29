<?php
/*
Plugin Name: Mass Upload Handling
Plugin URI: https://wordpress.org/plugins/wp-migrate-db/
Description: Collects selected upload types as JSON data updates dynamically.
Version: 1
Author URI: https://github.com/cokopoof
Text Domain: mass-upload-handling
*/


//Custom Functions
function collect_pdf_attachments() {
	//collect pdf attachment objects
	$pdfs = get_posts(array(
    'post_type' => 'attachment',
    'post_mime_type' => 'application/pdf',
    'posts_per_page' => -1
));

//create the json file to store the attachment data (need to add a check to see if it already exists)


$file_dir = get_template_directory_uri() . 'new_doc.json';
file_put_contents($file_dir, "{" . PHP_EOL);
//for each pdf attachment object, prepare for javascript and store the data then write it into the json file
foreach ($pdfs as  $pdf) {
    $pdf_data = wp_prepare_attachment_for_js($pdf->ID);

		if (end($pdfs) === $pdf) {
			$end_brace = "}";
		} else {
			$end_brace = "},";
		}
		$write_file = "\"" . $pdf_data['id'] . "\"" . ": {" . PHP_EOL
			            . "\"url\"" . ":" . "\"" . $pdf_data['url'] . "\"" . "," . PHP_EOL
			            . "\"title\"" . ":" .  "\"" . $pdf_data['title'] .  "\"" . "," . PHP_EOL
									. "\"filename\"" . ":" . "\"" . $pdf_data['filename'] . "\"" . PHP_EOL
									. $end_brace;

	  file_put_contents( $file_dir, $write_file, FILE_APPEND);
}
file_put_contents( $file_dir, "}", FILE_APPEND);
}

add_action("add_attachment", 'collect_pdf_attachments');
?>
