<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator;

use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

interface SearchDelegatorInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $queryCriteria
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function search(QueryInterface $queryCriteria, array $resultFormatters = [], array $requestParameters = []);

    /**
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int;

    /**
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array;

    /**
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName);

    /**
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(?string $indexName = null): bool;

    /**
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool;
}
