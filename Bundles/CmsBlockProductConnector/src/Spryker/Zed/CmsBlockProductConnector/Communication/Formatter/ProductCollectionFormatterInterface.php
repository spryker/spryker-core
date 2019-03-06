<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Formatter;

interface ProductCollectionFormatterInterface
{
    /**
     * @param array $suggestData
     *
     * @return array
     */
    public function prepareData(array $suggestData): array;
}
