<?php

declare(strict_types=1);

namespace Controller;

require_once(realpath(__DIR__ . '/../model/model.php'));

use function Model\get_csv_path;
use function Model\read_table;
use function Model\get_blog_entries;
use function Model\get_img_url_array;
use function Model\get_img_info_array;



require_once(realpath(__DIR__ . '/../view/viewlib.php'));

use function View\get_template_path;

require_once(realpath(__DIR__ . '/../../vendor/utils/utils.php'));

use function Utils\render_template;
use function Utils\api_call;




// ############################################################################
// Route handlers
// ############################################################################

// ----------------------------------------------------------------------------
function index(): string
{

    $index_body = render_template(get_template_path('/body/index'), []);
    $index_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'WebApp',
            'body'  => $index_body
        ]
    );
    return $index_view;
}

// ----------------------------------------------------------------------------
function blog(): string
{

    $blog_vars = get_blog_entries();


    $blog_body = render_template(get_template_path('/body/blog'), ['blog_entry' => $blog_vars]);
    //$blog_body ="<p>blog</p>";
    $blog_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Blog',
            'body'  => $blog_body
        ]
    );
    return $blog_view;
}

// ----------------------------------------------------------------------------
function gallery(): string
{

    // List files
    $local_file_array = glob(__DIR__ . '/../../db/gallery/*');


    // Categories images
    // $action_collection_array = glob('/dwes/movie-viewer-mvc/db/gallery/action/*');
    // $adventure_collection_array = glob('/dwes/movie-viewer-mvc/db/gallery/adventure/*');
    // $animation_collection_array = glob('/dwes/movie-viewer-mvc/db/gallery/animation/*');
    // $comedy_collection_array = glob('/dwes/movie-viewer-mvc/db/gallery/comedy/*');
    // $drama_collection_array = glob('/dwes/movie-viewer-mvc/db/gallery/drama/*');
    // $horror_collection_array = glob('/dwes/movie-viewer-mvc/db/gallery/horror/*');

    $gallery_vars = get_img_info_array($local_file_array);

    // $action_vars = get_img_info_array($action_collection_array);
    // $adventure_vars = get_img_info_array($adventure_collection_array);
    // $animation_vars = get_img_info_array($animation_collection_array);
    // $comedy_vars = get_img_info_array($comedy_collection_array);
    // $drama_vars = get_img_info_array($drama_collection_array);
    // $horror_vars = get_img_info_array($horror_collection_array);



    $gallery_body = render_template(get_template_path('/body/gallery'), $gallery_vars);
    $gallery_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Gallery',
            'body'  => $gallery_body
        ]
    );
    return $gallery_view;
}

// ----------------------------------------------------------------------------
function data(): string
{

    // 1. Get data
    $movies_table = read_table(get_csv_path('movies'), ',');

    // 2. Fill template with data
    $data_body = render_template(
        get_template_path('/body/data'),
        ['movies_table' => $movies_table]
    );

    $data_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Data',
            'body'  => $data_body
        ]
    );
    return $data_view;
}

// ----------------------------------------------------------------------------
function web_service(): string
{

    $api_key = "2c30fc277d011c88735381b4cf9c7ac2";
    $result_billboard = api_call("https://api.themoviedb.org/3/trending/all/day?api_key=", $api_key);



    $web_service_body = render_template(get_template_path('/body/web-service'), ['movies_array' => $result_billboard]);
    $web_service_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'WEB SERVICE',
            'body'  => $web_service_body
        ]
    );
    return $web_service_view;
}

// ----------------------------------------------------------------------------
function error_404(string $request_path): string
{

    http_response_code(404);

    $error404_body = render_template(
        get_template_path('/body/error404'),
        ['request_path' => $request_path]
    );

    $error404_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Not found',
            'body'  => $error404_body
        ]
    );

    return $error404_view;
}

// ----------------------------------------------------------------------------
