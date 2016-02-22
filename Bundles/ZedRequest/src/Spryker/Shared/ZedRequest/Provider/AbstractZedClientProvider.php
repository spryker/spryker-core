<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Provider;

use Spryker\Client\ZedRequest\Client\HttpClient;
use Spryker\Client\ZedRequest\Client\ZedClient;
use Spryker\Shared\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

/**
 * Class ZedRequestClientProvider
 *
 * @method \Spryker\Shared\ZedRequest\Client\AbstractZedClient getInstance()
 */
abstract class AbstractZedClientProvider extends AbstractClientProvider
{

    /**
     * @throws \Exception
     *
     * @return \Spryker\Shared\ZedRequest\Client\AbstractZedClient
     */
    protected function createZedClient()
    {
        $httpClient = new HttpClient(
            'http://' . Config::get(ZedRequestConstants::HOST_ZED_API),
            Config::get(ZedRequestConstants::TRANSFER_USERNAME),
            Config::get(ZedRequestConstants::TRANSFER_PASSWORD)
        );

        return new ZedClient($httpClient);
    }

}
