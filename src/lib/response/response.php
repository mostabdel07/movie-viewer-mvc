<?php
declare(strict_types=1);
namespace Response;

require_once(__DIR__ . '/../../app/config.php');
use function Config\get_lib_dir;

require_once(get_lib_dir() . '/utils/utils.php');
use function Utils\convert_to_string;



// ############################################################################
// Response Class
// ############################################################################


class Response {

    public mixed    $body;
    public ?int     $status_code;
    public ?string  $redirection_path;


    // Recommended usages:
    //   - new Response($view)
    //   - new Response($error_view, 404)
    //   - new Response(redirection_path: '/index')
    // Body:
    //   - Body can be an array or anything that can be cast to string.
    //   - If array it will be converted to json. If string it will be sent as html.
    //   - https://stackoverflow.com/questions/4064444/returning-json-from-a-php-script
    // Status code:
    //   - PHP will set a status code automatically if we don't do it.
    //   - For redirections, PHP will return a 302 code. No need to set it manually.
    // Redirection:
    //   - Do not set any other property if you want a redirection.
    //   - https://stackoverflow.com/questions/768431/how-do-i-make-a-redirect-in-php
    // ------------------------------------------------------------------------
    public function __construct(mixed   $body               = null,
                                ?int    $status_code        = null,
                                ?string $redirection_path   = null ) {

        $this->body             = $body;
        $this->status_code      = $status_code;
        $this->redirection_path = $redirection_path;
    }

    // ------------------------------------------------------------------------
    public function __toString(): string {

        $body_str = convert_to_string($this->body);

        $response_str = <<<END

            Body:
            $body_str
            Status code:
            $this->status_code
            Redirection path:
            $this->redirection_path

            END;

        return $response_str;
    }

    // Short helper functions
    // ------------------------------------------------------------------------
    public function is_redirection():     bool { return  !is_null($this->redirection_path); }
    public function has_status_code():    bool { return  !is_null($this->status_code);      }
    public function has_body():           bool { return  !is_null($this->body);             }
    public function has_array_body():     bool { return (!is_null($this->body)) and (is_array($this->body)); }


    // Redirection: https://stackoverflow.com/questions/768431/how-do-i-make-a-redirect-in-php
    // ------------------------------------------------------------------------
    public function send(): void {

        if ( $this->is_redirection()  ) { header('Location: ' . $this->redirection_path); return; }
        if ( $this->has_status_code() ) { http_response_code($this->status_code); }
        if ( $this->has_array_body()  ) { header("Content-Type: application/json"); }
        if ( $this->has_body()        ) { $body_str = convert_to_string($this->body); echo $body_str; }

    }

}
// ----------------------------------------------------------------------------
