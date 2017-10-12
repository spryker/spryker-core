<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Provider;

use Spryker\Shared\ZedRequest\Provider\AbstractZedClientProvider;

/**
 * @deprecated Will be removed in the next major version. Please use ZedRequestFactory.
 *
 * @package Spryker\Client\ZedRequest\Provider
 */
class ZedClientProvider extends AbstractZedClientProvider
{
    /**
     * @deprecated Will be removed in the next major version. Please use ZedRequestFactory->createClient().
     *
     * @return \Spryker\Shared\ZedRequest\Client\AbstractZedClient
     */
    public function createZedClient()
    {
        return parent::createZedClient();
    }
}
