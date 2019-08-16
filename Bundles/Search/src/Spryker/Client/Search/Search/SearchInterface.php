<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Search;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @TODO can be removed when refactored to ClientAdapterPlugins.
 */
interface SearchInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryCriteria
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function search(QueryInterface $queryCriteria, array $resultFormatters = [], array $requestParameters = []);
}
