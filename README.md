Guzzle Bundle KnpU OAuth2 Plugin
================================

This bundle integrates KnpU OAuth2 functionality using a Guzzle Bundle plugin.

This bundle was highly inspired by the work of this: [gregurco/guzzle-bundle-oauth2-plugin](https://github.com/gregurco/GuzzleBundleOAuth2Plugin).
The OAuth2 negociation to retrieved the AccessToken is handle with [knpuniversity/oauth2-client-bundle](https://github.com/knpuniversity/oauth2-client-bundle) and [league/oauth2-client](https://oauth2-client.thephpleague.com/).

## Installation

With composer:

```
$ composer require idci/guzzle-bundle-knpu-oauth2-plugin
```

## Enable bundle

Overrides the `registerBundles` function in `src/Kernel.php` like this:

```php
    public function registerBundles(): iterable
    {
        $contents = require $this->getBundlesPath();
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                if ($class === \EightPoints\Bundle\GuzzleBundle\EightPointsGuzzleBundle::class) {
                    yield new $class([
                        new \IDCI\Bundle\GuzzleBundleKnpUOAuth2Plugin\IDCIGuzzleBundleKnpUOAuth2Plugin(),
                    ]);
                } else {
                    yield new $class();
                }
            }
        }
    }
```

## Configuration

Configure your KnpUOAuth2 client as described into [the offical documentation](https://github.com/knpuniversity/oauth2-client-bundle#configuration) into `config/packages/knpu_oauth2_client.yaml`.
Here is an example using a Keycloak client:
```yaml
knpu_oauth2_client:
    clients:
        keycloak:
            type: keycloak
            auth_server_url: '%env(string:KEYCLOAK_SERVER_BASE_URL)%'
            realm: '%env(string:KEYCLOAK_REALM)%'
            client_id: '%env(string:KEYCLOAK_CLIENT_ID)%'
            client_secret: '%env(string:KEYCLOAK_CLIENT_SECRET)%'
            redirect_route: null
            redirect_params: { }
            # encryption_algorithm: null # Optional: Encryption algorith, i.e. RS256
            # encryption_key_path: null # Optional: Encryption key path, i.e. ../key.pem
            # encryption_key: null # Optional: Encryption key, i.e. contents of key or certificate
            # version: '20.0.1' # Optional: The keycloak version to run against
            # use_state: false # whether to check OAuth2 "state": defaults to true
```

Then when you wants to automatically add a bearer token to your Guzzle client request, simply use the `knpu_oauth2` plugin configuration into `config/packages/eight_points_guzzle.yaml`.
Here is an example that use the `keycloak` KnpUOAuth2 client:
```yaml
eight_points_guzzle:
    clients:
        my_guzzle_client:
            base_url: '%env(string:MY_GUZZLE_CLIENT_ENV_BASE_URL)%'
            options:
                auth: knpu_oauth2
            plugin:
                knpu_oauth2:
                    client: keycloak
```