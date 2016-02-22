<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Auth;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Auth\Token\TokenService;

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
