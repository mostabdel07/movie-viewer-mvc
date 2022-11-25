<?php
declare(strict_types=1);
namespace View;

require_once(realpath(__DIR__ . '/../../vendor/utils/utils.php'));
use function Utils\join_paths;



// ############################################################################
// Template functions
// ############################################################################

// Example: '/body/index' => '/app/src/view/body/index.template.php'
// ----------------------------------------------------------------------------
function get_template_path(string $template_id): string {

     $template_suffix        = '.template.php';
     $template_relative_path = $template_id . $template_suffix;

     $view_dir           = __DIR__;
     $template_full_path = join_paths($view_dir, $template_relative_path);

     return $template_full_path;
}



// ############################################################################
// HTML Converstion functions
// ############################################################################

// 1. Convert table header to string
// ----------------------------------------------------------------------------
function get_html_header(array $header): string {

     $add_th_tags = fn ($column_name) => "<th>$column_name</th>";

     $th_tags_array = array_map($add_th_tags, $header);
     $th_tags_str   = implode(' ', $th_tags_array);
     $html_header   = "<tr> $th_tags_str </tr>" . PHP_EOL;

     return $html_header;
}

// 2. Convert table row to string
// ----------------------------------------------------------------------------
function get_html_row(array $row): string {

     $add_td_tags = fn ($row_field)   => "<td>$row_field</td>";

     $td_tags_array = array_map($add_td_tags, $row);
     $td_tags_str   = implode(' ', $td_tags_array);
     $html_row   = "<tr> $td_tags_str </tr>" . PHP_EOL;

     return $html_row;
}

// 3. Convert table body to string
// ----------------------------------------------------------------------------
function get_html_body(array $body): string {

     $html_row_array = array_map('View\get_html_row', $body);
     $html_body      = implode('', $html_row_array);

     return $html_body;
}

// ----------------------------------------------------------------------------
