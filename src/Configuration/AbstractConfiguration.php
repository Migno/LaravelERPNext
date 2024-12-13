<?php

namespace STREETEC\LaravelERPNext\Configuration;

abstract class AbstractConfiguration implements ConfigurationInterface
{
    protected $domain;
    protected $user;
    protected $password;

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
