<?php

namespace Package\Oidc;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Date: 2022/9/1
 * @author George
 * @package Package\Oidc
 */
class Operator implements Authenticatable
{
    /**
     * All the user's attributes.
     *
     * @var array
     */
    protected array $attributes;

    /**
     * Create a new generic User object.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Date: 2022/9/1
     * @return string
     * @author George
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * Date: 2022/9/1
     * @return string
     * @author George
     */
    public function getAuthIdentifier(): string
    {
        return $this->attributes[$this->getAuthIdentifierName()];
    }

    /**
     * Date: 2022/9/1
     * @return null
     * @author George
     */
    public function getAuthPassword()
    {
        return null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        return null;
    }

    public function getRememberTokenName()
    {
        return null;
    }
}