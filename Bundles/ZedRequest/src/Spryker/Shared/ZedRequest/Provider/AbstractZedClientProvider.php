<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest\Provider;

use Spryker\Client\ZedRequest\Client\HttpClient;
use Spryker\Client\ZedRequest\Client\ZedClient;
use Spryker\Shared\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;
use Spryker\Shared\ZedRequest\Client\AbstractZedClient;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

/**
 * Class ZedRequestClientProvider
 *
 * @method AbstractZedClient getInstance()
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
