<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Provider;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;
use SprykerEngine\Shared\Kernel\AbstractClientProvider;
use SprykerFeature\Shared\ZedRequest\Client\AbstractZedClient;

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
    protected function createClient()
    {
        $httpClient = $this->factory->createClientHttpClient(
            $this->factory,
            $this->locator,
            'http://' . Config::get(SystemConfig::HOST_ZED_API),
            Config::get(YvesConfig::TRANSFER_USERNAME),
            Config::get(YvesConfig::TRANSFER_PASSWORD)
        );

        return $this->factory->createClientZedClient($httpClient);
    }

}
