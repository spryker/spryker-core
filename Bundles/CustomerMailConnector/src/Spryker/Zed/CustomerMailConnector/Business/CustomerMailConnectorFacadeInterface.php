<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\CustomerMailConnector\Business;

interface CustomerMailConnectorFacadeInterface
{

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function sendRegistrationToken($email, $token);

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function sendPasswordRestoreToken($email, $token);

    /**
     * @param string $email
     *
     * @return bool
     */
    public function sendPasswordRestoredConfirmation($email);

}
