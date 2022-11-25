<?php
declare(strict_types=1);
namespace Model;

require_once(realpath(__DIR__ . '/../../vendor/table/table.php'));
use Table\Table;

require_once(realpath(__DIR__ . '/../../vendor/utils/utils.php'));
use function Utils\join_paths;



// ############################################################################
// Table functions
// ############################################################################

// Example: '/manga' => '/app/db/manga.csv'
// ----------------------------------------------------------------------------
function get_csv_path(string $csv_id): string {

    $csv_suffix        = '.csv';
    $csv_relative_path = $csv_id . $csv_suffix;

    $db_dir        = realpath(join_paths(__DIR__, '/../../db/csv'));
    $csv_full_path = join_paths($db_dir, $csv_relative_path);

    return $csv_full_path;
}

// ----------------------------------------------------------------------------
function read_table(string $csv_filename, string $separator): Table {

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