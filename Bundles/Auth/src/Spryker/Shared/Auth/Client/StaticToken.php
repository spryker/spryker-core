<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Auth\Client;

abstract class StaticToken
{
    /**
     * @var string|null
     */
    protected $rawToken = null;

    /**
     * @param string $token
     *
     * @return void
     */
    public function setRawToken($token)
    {
        $this->rawToken = $token;
    }

    /**
     * @return string
     */
    public function getRawToken()
    {
        return $this->rawToken;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return base64_encode(password_hash($this->rawToken, PASSWORD_DEFAULT));
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    public function check($hash)
    {
        return password_verify($this->rawToken, base64_decode($hash));
    }
}
