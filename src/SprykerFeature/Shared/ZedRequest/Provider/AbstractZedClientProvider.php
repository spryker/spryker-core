<?php

namespace SprykerFeature\Shared\ZedRequest\Provider;

use \SprykerFeature\Shared\Library\Config;
use \SprykerFeature\Shared\System\SystemConfig;
use \SprykerFeature\Shared\Yves\YvesConfig;
use SprykerEngine\Shared\Kernel\AbstractClientProvider;
use SprykerFeature\Shared\ZedRequest\Client\AbstractZedClient;

/**
 * Class ZedRequestClientProvider
 * @package SprykerFeature\Shared\ZedRequest
 * @method AbstractZedClient getInstance()
 */
abstract class AbstractZedClientProvider extends AbstractClientProvider
{
    /**
     * @return AbstractZedClient
     * @throws \Exception
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
