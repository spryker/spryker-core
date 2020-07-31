<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Index;

use Elastica\Document;

interface IndexAdapterInterface
{
    /**
     * @param int|string $id
     * @param array $options
     *
     * @return \Elastica\Document
     */
    public function getDocument($id, array $options = []): Document;

    /**
     * @param \Elastica\Document[] $documents
     * @param array $options
     *
     * @return \Elastica\Bulk\ResponseSet
     */
    public function addDocuments(array $documents, array $options = []);

    /**
     * @return array
     */
    public function getMapping(): array;
}
