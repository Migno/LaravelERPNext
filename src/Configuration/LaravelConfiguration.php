<?php

namespace STREETEC\LaravelERPNext\Configuration;

use STREETEC\LaravelERPNext\Exception\InvalidCredentialsException;
use Illuminate\Support\Facades\Config;

class LaravelConfiguration extends AbstractConfiguration
{
    public function __construct()
    {
        $domain = Config::get('erpnext.domain');
        $user = Config::get('erpnext.username');
        $password = Config::get('erpnext.password');

        if (!\is_string($domain)) {
            throw new InvalidCredentialsException('Domain address must be provided');
        }

        if (!\is_string($user)) {
            throw new InvalidCredentialsException('Username must be provided');
        }

        if (!\is_string($password)) {
            throw new InvalidCredentialsException('Password must be provided');
        }

        $this->domain = $domain;
        $this->user = $user;
        $this->password = $password;
    }
}
