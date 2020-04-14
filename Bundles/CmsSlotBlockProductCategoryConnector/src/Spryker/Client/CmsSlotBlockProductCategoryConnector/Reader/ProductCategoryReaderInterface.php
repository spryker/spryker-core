<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader;

interface ProductCategoryReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getAbstractProductCategoryIds(int $idProductAbstract): array;
}
