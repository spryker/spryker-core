<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Handler;

use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @deprecated Will be removed without replacement.
 */
interface SearchHandlerInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryCriteria
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<mixed> $requestParameters
     *
     * @return \Elastica\ResultSet|array<mixed>
     */
    public function search(QueryInterface $queryCriteria, array $resultFormatters = [], array $requestParameters = []);
}
