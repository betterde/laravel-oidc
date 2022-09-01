<?php

namespace Package\Oidc;

use Illuminate\Http\Request;
use Lcobucci\JWT\Token\Parser;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Date: 2022/8/31
 * @author George
 * @package Package\Oidc
 */
class OIDCGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var Parser $parser
     */
    private Parser $parser;

    /**
     * @var Request $request
     */
    private Request $request;

    /**
     * @param UserProvider $provider
     * @param Request $request
     * @param Parser $parser
     */
    public function __construct(UserProvider $provider, Parser $parser, Request $request)
    {
        $this->parser = $parser;
        $this->request = $request;
        $this->provider = $provider;
    }

    /**
     * Date: 2022/8/31
     * @return bool
     * @author George
     */
    public function check(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Date: 2022/8/31
     * @return bool
     * @author George
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * Date: 2022/9/1
     * @return Authenticatable|null
     * @author George
     */
    public function user(): ?Authenticatable
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $default = Config::get('oidc.introspection.default');


        $user = match ($default) {
            'token' => $this->parseUserByToken(),
            'gateway' => $this->parseUserByUserinfoHeader(),
            'endpoints' => $this->callUserinfoEndpoint(),
        };

        return $this->user = $user;
    }

    /**
     * Date: 2022/8/31
     * @return string
     * @author George
     */
    public function id(): string
    {
        $id = null;

        if ($this->user() !== null) {
            $id = $this->user()->getAuthIdentifier();
        }

        return $id;
    }

    /**
     * Date: 2022/9/1
     * @param array $credentials
     * @return false
     * @author George
     */
    public function validate(array $credentials = []): bool
    {
        return false;
    }

    /**
     * Date: 2022/9/1
     * @return Authenticatable|null
     * @author George
     */
    public function parseUserByToken(): ?Authenticatable
    {
        $token = $this->getTokenForRequest();

        $token = $this->parser->parse($token);

        return $this->provider->retrieveByCredentials($token->claims()->all());
    }

    /**
     * Date: 2022/9/1
     * @return Authenticatable|null
     * @author George
     */
    public function parseUserByUserinfoHeader(): ?Authenticatable
    {
        $base64 = $this->request->header(Config::get('oidc.introspection.methods.gateway.header'));
        $json = base64_decode($base64);
        if ($json) {
            $claims = json_decode($json, true);
            return $this->provider->retrieveByCredentials($claims);
        }

        return null;
    }

    /**
     * Date: 2022/9/1
     * @return Authenticatable|null
     * @author George
     */
    public function callUserinfoEndpoint(): ?Authenticatable
    {
        return null;
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest(): string
    {
        $token = $this->request->header(Config::get('oidc.headers.id-token'));

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        return $token;
    }
}