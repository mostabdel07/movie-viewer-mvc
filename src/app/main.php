<?php

declare(strict_types=1);

namespace Main;

require_once(__DIR__ . '/config.php');

use function Config\get_lib_dir;

require_once(get_lib_dir() . '/request/request.php');

use Request\Request;

require_once(get_lib_dir() . '/cookie/cookie.php');

use Cookie\Cookie;

require_once(get_lib_dir() . '/context/context.php');

use Context\Context;
use function Context\get_new_browser_id;

require_once(get_lib_dir() . '/router/router.php');

use function Router\process_request;

require_once(get_lib_dir() . '/table/table.php');

use Table\Table;


// IMPORTANT: Server paths in $route_table must not end in a slash '/'. The root document is ''.
// ----------------------------------------------------------------------------
function main(): void
{

    // 1. Get request and route table
    $route_table = Table::readCSV(__DIR__ . '/routes.csv');
    $request     = Request::getFromWebServer();

    // 2. Check if the parameters have a key called 'browser_id' (cookie)
    $has_browser_id = key_exists('browser_id', $request->parameters);

    if (!$has_browser_id) {

        // 1. Create browser_id cookie
        $browser_id = get_new_browser_id();
        $browser_id_cookie = new Cookie('browser_id', $browser_id);

        // 2. Create  browser_id .json file
        $context = new Context();
        $context->writeToDisk($browser_id);

        // 3. Process request
        [$response, $context] = process_request($request, $context, $route_table);

        // 4. Save context
        $context->writeToDisk($browser_id);

        // 5. Add cookies
        $response->add_cookie($browser_id_cookie);

        // 6. Send response
        $response->send();
    } else { // has_browser_id

        // 1. Read context from disk
        $browser_id = $request->parameters['browser_id'];
        $context = Context::readFromDisk($browser_id);

        // 2. Process request
        [$response, $context] = process_request($request, $context, $route_table);

        // 3. Save context
        $context->writeToDisk($browser_id);

        // 4. Send response
        $response->send();
    }
}

// ----------------------------------------------------------------------------
main();
// ----------------------------------------------------------------------------
