<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Dependency\Plugin;

interface PasswordRestoredConfirmationSenderPluginInterface
{

    /**
     * @param string $email
     *
     * @return bool
     */
    public function send($email);

}
