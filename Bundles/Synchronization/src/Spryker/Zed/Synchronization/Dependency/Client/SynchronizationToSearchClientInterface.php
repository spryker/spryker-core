<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Dependency\Client;

interface SynchronizationToSearchClientInterface
{
    /**
     * @param string $key
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return mixed
     */
    public function read($key, $typeName = null, $indexName = null);

    /**
     * @param array $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function write(array $dataSet, $typeName = null, $indexName = null);

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeBulk(array $searchDocumentTransfers): bool;

    /**
     * @param array $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(array $dataSet, $typeName = null, $indexName = null);

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteBulk(array $searchDocumentTransfers): bool;
}
