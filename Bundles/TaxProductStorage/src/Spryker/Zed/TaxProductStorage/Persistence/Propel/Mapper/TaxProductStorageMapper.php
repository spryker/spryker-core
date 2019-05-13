<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;

class TaxProductStorageMapper
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $spyProductAbstract
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer $taxProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer
     */
    public function mapSpyProductAbstractToTaxProductStorageTransfer(
        SpyProductAbstract $spyProductAbstract,
        TaxProductStorageTransfer $taxProductStorageTransfer
    ): TaxProductStorageTransfer {

        return $taxProductStorageTransfer
            ->setSku($spyProductAbstract->getSku())
            ->setIdProductAbstract($spyProductAbstract->getIdProductAbstract())
            ->setIdTaxSet($spyProductAbstract->getFkTaxSet());
    }
}
