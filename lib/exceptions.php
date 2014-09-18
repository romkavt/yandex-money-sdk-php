<?php 
class FormatError extends Exception {
    public function __construct() {
        parent::__construct(
            "Request is missformated", 400
        );
    }

}

class ScopeError extends Exception {
    public function __construct() {
        parent::__construct(
            "Scope error. Obtain new access_token from user"
            . "with extended scope", 403
        );
    }

}

class TokenError extends Exception {
    public function __construct() {
        parent::__construct("Token is expired or incorrect", 401);
    }
}
