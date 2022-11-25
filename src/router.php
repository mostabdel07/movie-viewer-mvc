<?php
declare(strict_types=1);
namespace Router;

require_once(__DIR__ . '/controller/controller.php');
use Controller;


// - URL parts: https://www.geeksforgeeks.org/components-of-a-url/
// - Match paths only against routes without a trailing slash '/'. This is, only files, not dirs.
// - get_request_path() must remove trailing slashes of all request paths.
// - The root '/' document becomes the empty string ''. In that case we'll handle as '/index'.
// ----------------------------------------------------------------------------
function get_request_path(): string {

    $url_path           = $_SERVER['REQUEST_URI'];
    $sanitized_url_path = filter_var($url_path, FILTER_SANITIZE_URL);
    $trimmed_url_path   = rtrim($sanitized_url_path, '/');
   
    return $trimmed_url_path;
}


// IMPORTANT: Paths never end with a slash '/'. The root document is ''.
// ----------------------------------------------------------------------------
function make_response(string $request_path): string {

    $response = match($request_path) {

        '/index', ''    =>  Controller\index(),
        '/blog'         =>  Controller\blog(),
        '/gallery'      =>  Controller\gallery(),
        '/data'         =>  Controller\data(),
        '/web-service'  =>  Controller\web_service(),

        default         =>  Controller\error_404($request_path),
    };

    return $response;
}


// ----------------------------------------------------------------------------
function main(): void {

    // 1. Get URL path
    $request_path = get_request_path();

    // 2. Match URL path against registered functions
    $response = make_response($request_path);

    // 3. Send response to client
    echo $response;
}


// ----------------------------------------------------------------------------
main();
// ----------------------------------------------------------------------------
