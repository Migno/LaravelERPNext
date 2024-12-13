<?php

namespace STREETEC\LaravelERPNext\Configuration;

interface ConfigurationInterface
{
    public function getDomain(): string;
    public function getUser(): string;
    public function getPassword(): string;
}
