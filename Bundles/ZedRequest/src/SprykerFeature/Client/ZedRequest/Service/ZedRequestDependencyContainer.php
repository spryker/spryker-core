<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service;

use Generated\Client\Ide\FactoryAutoCompletion\ZedRequest;
use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Shared\Yves\YvesConfig;

/**
 * @method ZedRequest getFactory()
 */
class ZedRequestDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ZedClient
     */
    public function createClient()
    {
        $httpClient = $this->getFactory()->createClientHttpClient(
            $this->getFactory(),
            $this->getLocator(),
            'http://' . Config::get(SystemConfig::HOST_ZED_API)
        );

        return $this->getFactory()->createClientZedClient(
            $httpClient
        );
    }

}
