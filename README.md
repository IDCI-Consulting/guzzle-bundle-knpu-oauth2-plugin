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