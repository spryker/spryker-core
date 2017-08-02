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

/**
 * @method \Elastica\Client getInstance()
 */
abstract class AbstractSearchClientProvider extends AbstractClientProvider
{

    /**
     * @return \Elastica\Client
     */
    protected function createZedClient()
    {
        if (Config::hasValue(SearchConstants::ELASTICA_PARAMETER__EXTRA)) {
            $config = Config::get(SearchConstants::ELASTICA_PARAMETER__EXTRA);
        }

        $config['protocol'] = ucfirst(Config::get(SearchConstants::ELASTICA_PARAMETER__TRANSPORT));
        $config['port'] = Config::get(SearchConstants::ELASTICA_PARAMETER__PORT);
        $config['host'] = Config::get(SearchConstants::ELASTICA_PARAMETER__HOST);

        if (Config::hasValue(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . Config::get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER),
            ];
        }

        return (new Client($config));
    }

}
