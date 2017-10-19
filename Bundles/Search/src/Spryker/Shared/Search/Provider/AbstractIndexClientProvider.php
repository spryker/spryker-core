<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search\Provider;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Search\SearchConstants;

/**
 * @method \Elastica\Index getInstance()
 */
abstract class AbstractIndexClientProvider extends AbstractSearchClientProvider
{
    /**
     * @return \Elastica\Index
     */
    protected function createZedClient()
    {
        $client = parent::createZedClient();

        return $client->getIndex(Config::get(SearchConstants::ELASTICA_PARAMETER__INDEX_NAME));
    }
}
