<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Search;

use Elastica\ResultSet;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface SearchInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryCriteria
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $queryCriteria, array $resultFormatters = [], array $requestParameters = []): ResultSet;
}
