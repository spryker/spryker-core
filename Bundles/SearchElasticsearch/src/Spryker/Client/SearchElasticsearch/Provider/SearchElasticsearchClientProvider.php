<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Provider;

use Elastica\Client;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants;

class SearchElasticsearchClientProvider extends AbstractClientProvider
{
    /**
     * @return object
     */
    public function getClient()
    {
        return $this->getInstance();
    }

    /**
     * @return object
     */
    protected function createZedClient()
    {
        $config = $this->getClientConfig();

        return (new Client($config));
    }

    /**
     * TODO Config should be injected.
     *
     * @return array
     */
    protected function getClientConfig(): array
    {
        if (Config::hasValue(SearchElasticsearchConstants::CLIENT_CONFIGURATION)) {
            return Config::get(SearchElasticsearchConstants::CLIENT_CONFIGURATION);
        }

        if (Config::hasValue(SearchElasticsearchConstants::EXTRA)) {
            $config = Config::get(SearchElasticsearchConstants::EXTRA);
        }

        $config['transport'] = ucfirst(Config::get(SearchElasticsearchConstants::TRANSPORT));
        $config['port'] = Config::get(SearchElasticsearchConstants::PORT);
        $config['host'] = Config::get(SearchElasticsearchConstants::HOST);

        if (Config::hasValue(SearchElasticsearchConstants::AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . Config::get(SearchElasticsearchConstants::AUTH_HEADER),
            ];
        }

        return $config;
    }
}
