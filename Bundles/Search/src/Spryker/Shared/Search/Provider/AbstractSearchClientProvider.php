<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search\Provider;

use Elastica\Client;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;
use Spryker\Shared\Search\SearchConstants;

abstract class AbstractSearchClientProvider extends AbstractClientProvider
{
    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::HOST
     */
    protected const HOST = 'SEARCH_ELASTICSEARCH:HOST';

    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::PORT
     */
    protected const PORT = 'SEARCH_ELASTICSEARCH:PORT';

    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::TRANSPORT
     */
    protected const TRANSPORT = 'SEARCH_ELASTICSEARCH:TRANSPORT';

    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::AUTH_HEADER
     */
    protected const AUTH_HEADER = 'SEARCH_ELASTICSEARCH:AUTH_HEADER';

    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::EXTRA
     */
    protected const EXTRA = 'SEARCH_ELASTICSEARCH:EXTRA';

    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::CLIENT_CONFIGURATION
     */
    protected const CLIENT_CONFIGURATION = 'SEARCH_ELASTICSEARCH:CLIENT_CONFIGURATION';

    /**
     * @return object
     */
    protected function createZedClient()
    {
        $config = $this->getClientConfig();

        return (new Client($config));
    }

    /**
     * @return array
     */
    protected function getClientConfig()
    {
        if (Config::hasValue(SearchConstants::ELASTICA_CLIENT_CONFIGURATION)) {
            return Config::get(SearchConstants::ELASTICA_CLIENT_CONFIGURATION);
        }

        if (Config::hasValue(static::CLIENT_CONFIGURATION)) {
            return Config::get(static::CLIENT_CONFIGURATION);
        }

        $config = Config::get(SearchConstants::ELASTICA_PARAMETER__EXTRA, Config::get(static::EXTRA, []));

        $config['transport'] = ucfirst(Config::get(SearchConstants::ELASTICA_PARAMETER__TRANSPORT, Config::get(static::TRANSPORT)));
        $config['port'] = Config::get(SearchConstants::ELASTICA_PARAMETER__PORT, Config::get(static::PORT));
        $config['host'] = Config::get(SearchConstants::ELASTICA_PARAMETER__HOST, Config::get(static::HOST));

        $authHeader = (string)Config::get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER, Config::get(static::AUTH_HEADER, ''));

        if ($authHeader !== '') {
            $config['headers'] = [
                'Authorization' => 'Basic ' . Config::get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER),
            ];
        }

        return $config;
    }
}
