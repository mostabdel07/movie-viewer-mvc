<?php

declare(strict_types=1);

namespace Controller;

require_once(__DIR__ . '/../config.php');

use function Config\get_lib_dir;
use function Config\get_model_dir;
use function Config\get_view_dir;
use function Config\get_public_dir;
use function Config\get_utils_dir;

require_once(get_lib_dir() . '/request/request.php');

use Request\Request;

require_once(get_lib_dir() . '/response/response.php');

use Response\Response;

require_once(get_model_dir() . '/model.php');

use function Model\get_csv_path;
use function Model\read_table;
use function Model\add_blog_message;
use function Model\check_user_login;
use function Model\get_blog_entries;
use function Model\get_img_info_array;
use function Model\get_img_info_category;
use function Model\add_user;


require_once(get_view_dir() . '/view.php');

use function View\get_template_path;
use function View\render_template;


require_once(get_utils_dir() . '/utils.php');

use function Utils\api_call;



// ############################################################################
// Route handlers
// ############################################################################
// All controller functions receive $request, whether they use it or not.

// ----------------------------------------------------------------------------
function index(Request $request): Response
{

    $index_body = render_template(get_template_path('/body/index'), []);
    $index_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'WebApp',
            'body'  => $index_body
        ]
    );

    $response   = new Response($index_view);
    return $response;
}

// ----------------------------------------------------------------------------
function login(Request $request): Response
{
    // 1. If GET, send form
    if ($request->method == 'GET') {

        $login_body = render_template(
            get_template_path('/body/login'),
            []
        );
        $login_view = render_template(
            get_template_path('/skeleton/skeleton'),
            [
                'title' => 'Login',
                'body'  => $login_body
            ]
        );

        $response = new Response($login_view);
        return $response;

        // 2. If POST get form parameters
    } elseif ($request->method == 'POST') {

        $username = $request->parameters['username'];
        $password = $request->parameters['password'];

        // Look into users.csv if the username and password are corrects, etc.
        if (check_user_login($username, $password)) {
            $response = new Response('Logged!');
        } else {
            $response = new Response('Username or password is incorrect!');
        }

        return $response;
    }
}

// ----------------------------------------------------------------------------
function register(Request $request): Response
{
    // 1. If GET, send form
    if ($request->method == 'GET') {

        $register_body = render_template(
            get_template_path('/body/register'),
            []
        );
        $register_view = render_template(
            get_template_path('/skeleton/skeleton'),
            [
                'title' => 'Register',
                'body'  => $register_body
            ]
        );

        $response = new Response($register_view);
        return $response;

        // 2. If POST get form parameters
    } elseif ($request->method == 'POST') {

        $username = $request->parameters['username'];
        $password = $request->parameters['password'];

        // Look into users.csv if the username and password exist.
        if (check_user_login($username, $password)) {
            $response = new Response('User already exists!');
        } else {
            add_user(get_csv_path('users'), $username, $password);
            $response = new Response('Registration Successful!');
        }

        return $response;
    }
}

// ----------------------------------------------------------------------------
function blog(Request $request): Response
{

    // 1. If request is POST, add data
    if ($request->method == 'POST') {
        add_blog_message(get_csv_path('blog'), $request->parameters['message']);
    }

    // 2. Get data

    $blog_vars = get_blog_entries();

    // 3. Fill template with data
    $blog_body = render_template(
        get_template_path('/body/blog'),
        ['blog_entry' => $blog_vars]
    );
    $blog_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Blog',
            'body'  => $blog_body
        ]
    );

    // 4. Return response
    $response = new Response($blog_view);
    return $response;
}

// ----------------------------------------------------------------------------
function gallery(Request $request): Response
{
    $local_file_array = glob(__DIR__ . '/../../../db/gallery/*');

    $gallery_vars = get_img_info_array($local_file_array);

    $gallery_body = render_template(get_template_path('/body/gallery'), $gallery_vars);
    $gallery_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Gallery',
            'body'  => $gallery_body
        ]
    );

    $response = new Response($gallery_view);
    return $response;
}
// ----------------------------------------------------------------------------
function genre(Request $request): Response
{

    $genre = $request->parameters['genre'];

    $category_vars = glob(get_public_dir() . "/img/$genre/*");

    //si el array está vacio mandar un template de error avisando que el genero no existe

    $action_vars = get_img_info_category($category_vars, $genre);

    $gallery_body = render_template(get_template_path('/body/category'), $action_vars);
    $gallery_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => $genre,
            'body'  => $gallery_body
        ]
    );

    $response = new Response($gallery_view);
    return $response;
}

// ----------------------------------------------------------------------------
function data(Request $request): Response
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

    $response = new Response($data_view);
    return $response;
}

// ----------------------------------------------------------------------------
function web_service(Request $request): Response
{
    $api_key = "2c30fc277d011c88735381b4cf9c7ac2";
    $result_billboard = api_call("https://api.themoviedb.org/3/trending/all/day?api_key=", $api_key);

    $web_service_body = render_template(get_template_path('/body/web-service'), ['movies_array' => $result_billboard]);
    $web_service_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Data',
            'body'  => $web_service_body
        ]
    );

    $response = new Response($web_service_view);
    return $response;
}

// ----------------------------------------------------------------------------
function error_404(Request $request): Response
{

    $error404_body = render_template(
        get_template_path('/body/error404'),
        ['request_path' => $request->path]
    );

    $error404_view = render_template(
        get_template_path('/skeleton/skeleton'),
        [
            'title' => 'Not found',
            'body'  => $error404_body
        ]
    );

    $response = new Response($error404_view, 404);
    return $response;
}

// ----------------------------------------------------------------------------

function about(Request $request): Response
{

    $authors = ["victor", "mostafa"];


    $response = new Response($authors, 404);
    return $response;
}

// ----------------------------------------------------------------------------
function contact(Request $request): Response
{
    // 1. If GET, send form
    if ($request->method == 'GET') {

        $contact_body = render_template(
            get_template_path('/body/contact'),
            []
        );
        $contact_view = render_template(
            get_template_path('/skeleton/skeleton'),
            [
                'title' => 'Contact',
                'body'  => $contact_body
            ]
        );

        $response = new Response($contact_view);
        return $response;

        // 2. If POST get form parameters
    }
}

// ----------------------------------------------------------------------------