<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Search\Provider;

use Elastica\Index;
use Elastica\Client;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Application\ApplicationConfig;
use SprykerEngine\Shared\Kernel\AbstractClientProvider;

/**
 * Class ClientStorageProvider
 *
 * @method Index getInstance()
 */
abstract class AbstractIndexClientProvider extends AbstractClientProvider
{

    /**
     * @throws \Exception
     *
     * @return Index
     */
    protected function createClient()
    {
        return (new Client([
            'protocol' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__TRANSPORT),
            'port' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(ApplicationConfig::ELASTICA_PARAMETER__HOST),
        ]))->getIndex(Config::get(ApplicationConfig::ELASTICA_PARAMETER__INDEX_NAME));
    }

}
