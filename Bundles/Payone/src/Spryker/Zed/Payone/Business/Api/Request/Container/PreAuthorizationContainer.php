<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container;

use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;

class PreAuthorizationContainer extends AbstractAuthorizationContainer
{

    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION;

}
