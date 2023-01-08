<?php

declare(strict_types=1);

namespace Model;

require_once(__DIR__ . '/../config.php');

use function Config\get_lib_dir;
use function Config\get_csv_dir;

require_once(get_lib_dir() . '/table/table.php');
require_once(get_lib_dir() . '/user/user.php');

use Table\Table;
use User\User;

require_once(get_lib_dir() . '/utils/utils.php');

use function Utils\join_paths;
use function Utils\read_json;

use \DateTimeImmutable as DTI;



// ############################################################################
// Table functions
// ############################################################################

// Example: '/manga' => '/app/db/manga.csv'
// ----------------------------------------------------------------------------
function get_csv_path(string $csv_id): string
{

    $csv_suffix        = '.csv';
    $csv_relative_path = $csv_id . $csv_suffix;
    $csv_full_path     = join_paths(get_csv_dir(), $csv_relative_path);

    return $csv_full_path;
}

// ----------------------------------------------------------------------------
function read_table(string $csv_filename, string $separator): Table
{

    $data = Table::readCSV($csv_filename, $separator);
    return $data;
}

// ----------------------------------------------------------------------------
function add_blog_message(string $csv_filename, string $message): void
{

    // 1. Read Table
    $blog_data = Table::readCSV($csv_filename);

    // 2. Get current time
    $timestamp     = new DTI('now');
    $timestamp_str = $timestamp->format(DTI::RFC3339);

    // 3. Append new row
    $blog_data->prependRow([$timestamp_str, $message]);

    // 4. Write Table
    $blog_data->writeCSV($csv_filename);
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


function get_img_info_category(array $local_file_array, string $genre): array
{

    $get_clean_path = fn ($path) => "/img/$genre/" . basename($path);
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

//-------------------- Login Methods -----------------------------///

function check_user_login(string $username, string $password): mixed
{

    $users_list = User::getUsersList(get_csv_path('users'));

    // Search for the credentials in the users list and return true if find it
    foreach ($users_list as $user) {
        if ($username == $user->getUsername() && $password == $user->getPassword()) {
            return $user;
        }
    }

    return null;
}

// ----------------------------------------------------------------------------
function add_user(string $csv_filename, string $username, string $password): void
{
    $newUser = new User($username, $password);

    $handle = fopen($csv_filename, "a");

    fputcsv($handle, ["\n"]);
    fputcsv($handle, [$username, $password]);

    fclose($handle);
}
