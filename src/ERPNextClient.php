<?php

namespace STREETEC\LaravelERPNext;

use Curl\Curl;
use STREETEC\LaravelERPNext\Configuration\ConfigurationInterface;
use STREETEC\LaravelERPNext\Exception\AuthorizationException;

/**
 * Class ERPNextClient
 *
 * @package STREETEC\LaravelERPNext
 */
class ERPNextClient
{
    protected $user;
    protected $password;
    protected $curl;
    protected $cookies = [];
    protected $baseUrl;

    protected function resetBaseUrl(): void
    {
        $this->baseUrl = null;
        $this->curl->setUrl($this->baseUrl);
    }

    public function __construct(ConfigurationInterface $config)
    {
        $this->baseUrl = rtrim($config->getDomain(), '/\\ ');
        $this->curl = new Curl($this->baseUrl);
        $this->curl->setHeader('Accept', 'application/json');
        $this->curl->setJsonDecoder(function ($response) {
            return json_decode($response, true, 512, JSON_ERROR_NONE);
        });
        $this->user = $config->getUser();
        $this->password = $config->getPassword();
    }

    public function authenticate(): ?array
    {
        $query = [
            'usr' => $this->user,
            'pwd' => $this->password
        ];

        $this->resetBaseUrl();
        $this->curl->post('/api/method/login', $query);

        if ($this->curl->error) {
            throw new AuthorizationException('Failed to authenticate with credentials provided.');
        }

        $this->cookies = $this->curl->getResponseCookies();
        $this->curl->setCookies($this->cookies);

        return $this->curl->response;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getCurlInstance(): Curl
    {
        return $this->curl;
    }

    public function isAuthenticated(): bool
    {
        $this->resetBaseUrl();
        $this->curl->get('/api/method/frappe.auth.get_logged_user');
        return $this->curl->httpStatusCode === 200;
    }

    public function getResource(string $resourceName, array $fields = [], array $filters = [], int $limitStart = 0, int $limitLength = 20): array
    {
        $query = [
            'limit_start' => $limitStart,
            'limit_page_length' => $limitLength,
        ];

        if (!empty($fields)) {
            $query['fields'] = json_encode($fields);
        }

        if (!empty($filters)) {
            $query['filters'] = json_encode($filters);
        }

        $this->resetBaseUrl();
        $this->curl->get('/api/resource/' . $resourceName, $query);

        if ($this->curl->error) {
            return [];
        }

        return $this->curl->response['data'] ?? [];
    }

    public function createResource(string $resourceName, array $data = []): array
    {
        $this->resetBaseUrl();
        $this->curl->post('/api/resource/' . $resourceName, 'data=' . json_encode($data));

        if ($this->curl->error) {
            return [];
        }

        return $this->curl->response['data'] ?? [];
    }

    public function updateResource(string $resourceName, string $name, array $data = []): array
    {
        $this->resetBaseUrl();
        $this->curl->put('/api/resource/' . $resourceName . '/' . $name, 'data=' . json_encode($data));

        if ($this->curl->error) {
            return [];
        }

        return $this->curl->response['data'] ?? [];
    }

    public function deleteResource(string $resourceName, string $name): bool
    {
        $this->resetBaseUrl();
        $this->curl->delete('/api/resource/' . $resourceName . '/' . $name);

        if ($this->curl->error) {
            return false;
        }

        return ($this->curl->response['message'] ?? null) === 'ok';
    }
}
