<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator;

use Spryker\Client\Search\Search\SearchInterface;
use Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface;

interface SearchDelegatorInterface extends SearchInterface
{
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
     * @return \Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface
     */
    public function delete(?string $indexName = null): ResponseInterface;

    /**
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool;
}
