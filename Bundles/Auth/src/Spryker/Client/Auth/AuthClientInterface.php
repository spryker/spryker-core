<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Auth;

interface AuthClientInterface
{

    /**
     * @param string $rawToken
     *
     * @return string
     */
    public function generateToken($rawToken);

    /**
     * @param string $rawToken
     * @param string $token
     *
     * @return bool
     */
    public function checkToken($rawToken, $token);

}
