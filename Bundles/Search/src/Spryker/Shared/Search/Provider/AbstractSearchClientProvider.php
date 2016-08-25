<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search\Provider;

use Elastica\Client;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;

/**
 * Class ClientStorageProvider
 *
 * @method \Elastica\Client getInstance()
 */
abstract class AbstractSearchClientProvider extends AbstractClientProvider
{

    /**
     * @return \Elastica\Client
     */
    protected function createZedClient()
    {
        $config = [
            'transport' => ucfirst(Config::get(ApplicationConstants::ELASTICA_PARAMETER__TRANSPORT)),
            'port' => Config::get(ApplicationConstants::ELASTICA_PARAMETER__PORT),
            'host' => Config::get(ApplicationConstants::ELASTICA_PARAMETER__HOST),
        ];

        if (Config::hasValue(ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER)) {
            $config['headers'] = [
                'Authorization' => 'Basic ' . Config::get(ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER)
            ];
        }

        return (new Client($config));
    }

}
