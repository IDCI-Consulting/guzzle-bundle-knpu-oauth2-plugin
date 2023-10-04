<?php

namespace IDCI\Bundle\GuzzleBundleKnpUOAuth2Plugin\Middleware;

use GuzzleHttp\Promise\PromiseInterface;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

class OAuth2Middleware
{
    private ?OAuth2ClientInterface $client;

    private ?AdapterInterface $cache;

    public function __construct()
    {
        $this->client = null;
        $this->cache = null;
    }

    public function setClient(OAuth2ClientInterface $client)
    {
        $this->client = $client;
    }

    public function setCache(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function onBefore()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                if (isset($options['auth']) && 'knpu_oauth2' == $options['auth']) {
                    $token = $this->getAccessToken();

                    if (null !== $token) {
                        $request = $request->withAddedHeader('Authorization', sprintf('Bearer %s', $token->getToken()));
                    }
                }

                return $handler($request, $options);
            };
        };
    }

    public function getAccessToken(): ?AccessTokenInterface
    {
        $accessToken = null;

        if (null !== $this->cache) {
            $accessToken = $this->cache->get('access_token', function (ItemInterface $item): AccessTokenInterface {
                $accessToken = $this->client->getOAuth2Provider()->getAccessToken('client_credentials');
                $expireAt = \DateTime::createFromFormat('U', $accessToken->getExpires());
                $item->expiresAt($expireAt);

                return $accessToken;
            });
        } else {
            $accessToken = $this->client->getOAuth2Provider()->getAccessToken('client_credentials');
        }

        if ($accessToken instanceof AccessTokenInterface && !$accessToken->hasExpired()) {
            return $accessToken;
        }

        return null;
    }
}