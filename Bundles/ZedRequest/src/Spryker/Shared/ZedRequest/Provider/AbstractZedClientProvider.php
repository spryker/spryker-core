<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest\Provider;

use Spryker\Client\ZedRequest\Client\HttpClient;
use Spryker\Client\ZedRequest\Client\ZedClient;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;
use Spryker\Shared\ZedRequest\Client\AbstractZedClient;

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
     * @return AbstractZedClient
     */
    protected function createZedClient()
    {
        $httpClient = new HttpClient(
            $this->locator,
            'http://' . Config::get(ApplicationConstants::HOST_ZED_API),
            Config::get(ApplicationConstants::TRANSFER_USERNAME),
            Config::get(ApplicationConstants::TRANSFER_PASSWORD)
        );

        return new ZedClient($httpClient);
    }

}
