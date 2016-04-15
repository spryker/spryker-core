<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Auth;

use Spryker\Client\Auth\Token\TokenService;
use Spryker\Client\Kernel\AbstractFactory;

class AuthFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Auth\Token\TokenService
     */
    public function createTokenService()
    {
        return new TokenService();
    }

}
