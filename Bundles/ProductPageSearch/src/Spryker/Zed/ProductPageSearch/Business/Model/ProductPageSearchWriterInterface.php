<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Model;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch;

interface ProductPageSearchWriterInterface
{
    /**
     * @return void
     */
    public function commitRemaining(): void;

    /**
     * @param array<\Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch> $productAbstractPageSearchEntities
     *
     * @return void
     */
    public function deleteProductAbstractPageSearchEntities(array $productAbstractPageSearchEntities);

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array<string, mixed> $data
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productPageSearchEntity
     *
     * @return void
     */
    public function save(ProductPageSearchTransfer $productPageSearchTransfer, array $data, SpyProductAbstractPageSearch $productPageSearchEntity);
}
