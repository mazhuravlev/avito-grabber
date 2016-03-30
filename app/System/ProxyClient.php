<?php

namespace App\System;


use App\Proxy;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

class ProxyClient
{

    private $client;
    private $proxy = null;

    public function __construct(Client $client, Proxy $proxy = null)
    {
        $this->client = $client;
        if ($proxy) {
            $this->proxy = $proxy;
        }
    }

    public function get($url, $options = [])
    {
        /** @var Proxy $proxy */
        $proxy = $this->proxy ? $this->proxy : Proxy::best()->first();
        $triesLeft = 100;
        $usedProxies = [];
        while ($triesLeft) {
            try {
                if (!$proxy) {
                    $proxy = Proxy::best()->first();
                }
                if ($proxy) {
                    printf('PROXY: %s:%s (%d hits/ %d fails): %s' . PHP_EOL, $proxy->ip, $proxy->port, $proxy->hits, $proxy->fails, $url);
                    return $this->withProxy($proxy, $url, $options);
                }
            } catch (ProxyException $e) {
                printf('PROXY FAIL: %s' . PHP_EOL, $e->getPrevious()->getMessage(), $proxy->ip, $proxy->port, $proxy->hits, $proxy->fails);
                $triesLeft--;
                array_push($usedProxies, $proxy->id);
                $proxy = Proxy::best()->whereNotIn('id', $usedProxies)->first();
                if (!$proxy) {
                    array_shift($usedProxies);
                }
            }
        }
        throw new ProxyException('retry limit');
    }

    private function withProxy(Proxy $proxy, $url, array $options)
    {
        $proxy->hit();
        $options['proxy'] = ['https' => self::formatProxy($proxy)];
        $options['connect_timeout'] = 5;
        $options['timeout'] = 5;
        //$options['debug'] = true;
        $response = null;
        try {
            $response = $this->client->get($url, $options);
        } catch (ServerException $e) {
            $proxy->fail();
            throw new ProxyException('', null, $e);
        } catch (ClientException $e) {
            $proxy->fail();
            throw new ProxyException('', null, $e);
        } catch (RequestException $e) {
            $proxy->fail();
            throw new ProxyException('', null, $e);
        }
        return $response;
    }

    private static function formatProxy(Proxy $proxy)
    {
        if ($proxy->login) {
            return sprintf('%s:%s@%s:%s', $proxy->login, $proxy->password, $proxy->ip, $proxy->port);
        } else {
            return sprintf('%s:%s', $proxy->ip, $proxy->port);
        }
    }

}