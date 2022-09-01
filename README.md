# Introduction

A OpenID connect client for Laravel framework.

# Install

```shell
composer require package/oidc:^1.0
```

# Configuration

```shell
php artisan vendor:publish --tag=oidc:config
```

```php
return [
    'headers' => [
        'rules' => 'urn:zitadel:iam:org:project:roles',
        'id-token' => env('OIDC_ID_TOKEN_HEADER', 'X-ID-TOKEN'),
        'access-token' => env('OIDC_ACCESS_TOKEN_HEADER', 'X-ACCESS-TOKEN'),
    ],
    'introspection' => [
        'issuer' => 'https://sjmsdev.cn:443',
        'default' => env('OIDC_INTROSPECTION_METHOD', 'gateway'),
        'methods' => [
            'token' => [
                'jwk' => []
            ],
            'gateway' => [
                'header' => env('OIDC_USERINFO_HEADER', 'X-Userinfo'),
                'encode' => 'base64'
            ],
            'endpoints' => [
                'config' => env('OIDC_CONFIG_ENDPOINT', 'https://sjmsdev.cn/.well-known/openid-configuration'),
                'userinfo' => env('OIDC_USERINFO_ENDPOINT', 'https://sjmsdev.cn/oidc/v1/userinfo')
            ]
        ]
    ],
];
```
