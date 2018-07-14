<?php

class Client
{
    protected $_token;
    protected $_client;

    public function __construct($url, $username, $password)
    {
        $this->_client = new GuzzleHttp\Client(['base_uri' => $url]);

        try {
            $response = $this->_client->request('POST', '/rest/V1/integration/admin/token', [
                'json' => [
                    'username' => $username,
                    'password' => $password
                ]
            ]);
            $this->_token = str_replace('"', '', $response->getBody()->getContents());
        } catch (GuzzleHttp\Exception\ClientException $e) {
            echo $e->getRequest();
            if ($e->hasResponse()) echo $e->getResponse();
        }

        return $this;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function getClient()
    {
        return $this->_client;
    }
}
