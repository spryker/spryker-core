<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Search\Provider;

use Elastica\Client;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Application\ApplicationConfig;
use SprykerEngine\Shared\Kernel\AbstractClientProvider;

/**
 * Class ClientStorageProvider
 *
 * @method Client getInstance()
 */
abstract class AbstractSearchClientProvider extends AbstractClientProvider
{

    /**
     * @throws \Exception
     *
     * @return Client
     */
    protected function createClient()
    {
        return (new Client([
            'protocol' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__TRANSPORT),
            'port' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__HOST),
        ]));
    }

}
