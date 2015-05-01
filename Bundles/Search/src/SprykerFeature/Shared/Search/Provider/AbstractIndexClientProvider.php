<?php

namespace SprykerFeature\Shared\Search\Provider;

use Elastica\Index;
use Elastica\Client;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerEngine\Shared\Kernel\AbstractClientProvider;

/**
 * Class ClientStorageProvider
 * @package SprykerFeature\Shared\Search
 * @method Index getInstance()
 */
abstract class AbstractIndexClientProvider extends AbstractClientProvider
{
    /**
     * @return Index
     * @throws \Exception
     */
    protected function createClient()
    {
        return (new Client([
            'protocol' => Config::get(SystemConfig::ELASTICA_PARAMETER__TRANSPORT),
            'port' => Config::get(SystemConfig::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(SystemConfig::ELASTICA_PARAMETER__HOST),
        ]))->getIndex(Config::get(SystemConfig::ELASTICA_PARAMETER__INDEX_NAME));
    }
}
