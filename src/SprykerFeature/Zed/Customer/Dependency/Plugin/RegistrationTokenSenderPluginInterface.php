<?php

namespace SprykerFeature\Zed\Customer\Dependency\Plugin;

interface RegistrationTokenSenderPluginInterface
{
    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function send($email, $token);
}
