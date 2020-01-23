<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Business\IndexMetaDataReader;

interface IndexMetaDataReaderInterface
{
    /**
     * @param string $indexName
     *
     * @return array
     */
    public function getIndexMetaData(string $indexName): array;
}
