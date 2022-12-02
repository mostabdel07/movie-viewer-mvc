<?php

declare(strict_types=1);

namespace Model;

require_once(realpath(__DIR__ . '/../../vendor/table/table.php'));

use Table\Table;

require_once(realpath(__DIR__ . '/../../vendor/utils/utils.php'));

use function Utils\join_paths;
use function Utils\read_json;




// ############################################################################
// Table functions
// ############################################################################

// Example: '/manga' => '/app/db/manga.csv'
// ----------------------------------------------------------------------------
function get_csv_path(string $csv_id): string
{

    $csv_suffix        = '.csv';
    $csv_relative_path = $csv_id . $csv_suffix;

    $db_dir        = realpath(join_paths(__DIR__, '/../../db/csv'));
    $csv_full_path = join_paths($db_dir, $csv_relative_path);

    return $csv_full_path;
}

// ----------------------------------------------------------------------------
function read_table(string $csv_filename, string $separator): Table
{

    $data = Table::readCSV($csv_filename, $separator);
    return $data;
}

// ----------------------------------------------------------------------------
function read_csv($csv_movies_path): array
{
    $lines = explode("\n", file_get_contents($csv_movies_path));
    $headers = str_getcsv(array_shift($lines));
    $data = array();
    foreach ($lines as $line) {

        $row = array();

        foreach (str_getcsv($line) as $key => $field)
            $row[$headers[$key]] = $field;

        $row = array_filter($row);

        $data[] = $row;
    }
    return $data;
}

// ----------------------------------------------------------------------------

function get_blog_entries(): array
{
    $blog_entries_path = (array_reverse(glob("/dwes/movie-viewer-mvc/db/blog/*"))); // PATH ??

    $blog_entries = [];



    foreach ($blog_entries_path as $path) {
        $entry = read_json($path);
        $blog_entries[] = $entry;
    }

    return $blog_entries;
}


//-------------------- Gallery Methods -----------------------------///

function get_img_url_array(array $local_file_array): array
{
    // Filter and keep only image files
    $check_is_image    = fn ($file) => preg_match('~(jpg|jpeg|png)$~i', basename($file));
    $local_image_array = array_values(array_filter($local_file_array, $check_is_image));


    return $local_image_array;
}

function get_img_info_array(array $local_file_array): array
{

    $get_clean_path = fn ($path) => "/img/" . basename($path);
    $local_image_array = get_img_url_array($local_file_array);
    $images_path = array_map($get_clean_path, $local_image_array);

    // Create alt texts
    $make_alt_text = fn ($file) => pathinfo($file)['filename'];
    $web_alt_array = array_map($make_alt_text, $local_image_array);

    $imgInfoArray = [
        'image_array' => $images_path,
        'alt_array'   => $web_alt_array
    ];
    return $imgInfoArray;
}
