<?php

namespace Nortic\BruteForceProtection\SabreDavBackend;

use Nortic\BruteForceProtection\Core;
use Sabre\HTTP;
use Sabre\HTTP\RequestInterface;
use Sabre\HTTP\ResponseInterface;
use Sabre\DAV\Auth\Backend\BackendInterface;

class BasicCallBack implements BackendInterface
{
    protected $bruteForceProtection;
    protected $callBack;

    protected $realm = 'sabre/dav';
    protected $principalPrefix = 'principals/';

    public function __construct(Core $bruteForceProtection, callable $callBack)
    {
        $this->bruteForceProtection = $bruteForceProtection;
        $this->callBack = $callBack;
    }

    public function check(RequestInterface $request, ResponseInterface $response) 
    {
        $auth = new HTTP\Auth\Basic(
            $this->realm,
            $request,
            $response
        );

        $userpass = $auth->getCredentials();

        if ( ! $this->bruteForceProtection->check()) {

            return [false, 'Locked by BruteForce Protection : '. implode(', ', $this->bruteForceProtection->getMessages())];
        }

        if ( ! $userpass) {

            return [false, "No 'Authorization: Basic' header found. Either the client didn't send one, or the server is misconfigured"];
        }

        if ( ! $userpass[0] && ! $userpass[1]) {

            return [false, "Username or password unknown"];
        }

        if ( ! $this->validateUserPass($userpass[0], $userpass[1])) {

            $this->bruteForceProtection->failedLogin();

            return [false, "Username or password was incorrect"];
        }

        $this->bruteForceProtection->successLogin();

        return [true, $this->principalPrefix . $userpass[0]];
    }

    public function challenge(RequestInterface $request, ResponseInterface $response) 
    {
        if ( ! $this->bruteForceProtection->check()) {
            return;
        }

        $auth = new HTTP\Auth\Basic(
            $this->realm,
            $request,
            $response
        );
        $auth->requireLogin();
    }

    protected function validateUserPass($username, $password) 
    {
        $cb = $this->callBack;
        return $cb($username, $password);
    }
}
