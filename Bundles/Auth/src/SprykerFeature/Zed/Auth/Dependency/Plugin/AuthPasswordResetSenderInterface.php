<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Dependency\Plugin;

interface AuthPasswordResetSenderInterface
{
    /**
     * @param string $email
     * @param string $token
     *
     * @return mixed
     */
    public function send($email, $token);
}
