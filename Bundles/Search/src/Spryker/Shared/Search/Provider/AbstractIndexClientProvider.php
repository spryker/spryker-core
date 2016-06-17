<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search\Provider;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

/**
 * Class ClientStorageProvider
 *
 * @method \Elastica\Index getInstance()
 */
abstract class AbstractIndexClientProvider extends AbstractSearchClientProvider
{

    /**
     * @throws \Exception
     *
     * @return \Elastica\Index
     */
    protected function createZedClient()
    {
        $client = parent::createZedClient();

        return $client->getIndex(Config::get(ApplicationConstants::ELASTICA_PARAMETER__INDEX_NAME));
    }

}
