<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Business\DocumentCounter;

interface DocumentCounterInterface
{
    /**
     * @param string $indexName
     *
     * @return int
     */
    public function getTotalCountOfDocumentsInIndex(string $indexName): int;
}
