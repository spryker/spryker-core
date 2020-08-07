<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search\Provider;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Search\SearchConstants;

abstract class AbstractIndexClientProvider extends AbstractSearchClientProvider
{
    /**
     * @param string|null $index
     *
     * @return \Elastica\Index
     */
    protected function createZedClient($index = null)
    {
        $client = parent::createZedClient();

        $index = ($index !== null) ? $index : Config::get(SearchConstants::ELASTICA_PARAMETER__INDEX_NAME, sprintf('%s_search', strtolower(APPLICATION_STORE)));

        return $client->getIndex($index);
    }
}
