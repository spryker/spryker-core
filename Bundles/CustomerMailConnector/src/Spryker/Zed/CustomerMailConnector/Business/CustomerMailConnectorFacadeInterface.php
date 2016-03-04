<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerMailConnector\Business;

interface CustomerMailConnectorFacadeInterface
{

    /**
     * @api
     *
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function sendRegistrationToken($email, $token);

    /**
     * @api
     *
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function sendPasswordRestoreToken($email, $token);

    /**
     * @api
     *
     * @param string $email
     *
     * @return bool
     */
    public function sendPasswordRestoredConfirmation($email);

}
