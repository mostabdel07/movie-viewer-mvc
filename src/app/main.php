<?php

declare(strict_types=1);

namespace Main;

require_once(__DIR__ . '/config.php');

use function Config\get_lib_dir;

require_once(get_lib_dir() . '/request/request.php');

use Request\Request;

require_once(get_lib_dir() . '/router/router.php');

use function Router\process_request;

require_once(get_lib_dir() . '/table/table.php');

use Table\Table;


// IMPORTANT: Server paths in $route_table must not end in a slash '/'. The root document is ''.
// ----------------------------------------------------------------------------
function main(): void
{

    // 1. Get request and route table
    $request     = Request::getFromWebServer();
    $route_table = Table::readCSV(__DIR__ . '/routes.csv');

    // 2. Process request
    $response = process_request($request, $route_table);

    // 3. Send response
    setcookie("theme", "light");
    $response->send();
}

// ----------------------------------------------------------------------------
main();
// ----------------------------------------------------------------------------
