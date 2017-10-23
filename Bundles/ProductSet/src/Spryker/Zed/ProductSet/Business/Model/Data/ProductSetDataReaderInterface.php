<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data;

use Orm\Zed\ProductSet\Persistence\SpyProductSetData;

interface ProductSetDataReaderInterface
{
    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetData $productSetDataEntity
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    public function getLocalizedData(SpyProductSetData $productSetDataEntity);
}
