<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductBarcodeGuiQueryContainerInterface extends QueryContainerInterface
{
    public const COL_PRODUCT_NAME = 'PRODUCT_NAME';

    /**
     * @api
     *
     * @uses SpyProductQuery
     * @uses SpyProductLocalizedAttributesQuery
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function prepareTableQuery(LocaleTransfer $localeTransfer): SpyProductQuery;
}
