<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Transfer;

use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Propel\Runtime\Collection\ObjectCollection;

interface ProductImageTransferMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductImage\Persistence\SpyProductImageSet[] $productImageSetEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function mapProductImageSetCollection(ObjectCollection $productImageSetEntityCollection);

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function mapProductImageSet(SpyProductImageSet $productImageSetEntity);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductImage\Persistence\SpyProductImage[] $productImageEntityCollection
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return array<\Generated\Shared\Transfer\ProductImageTransfer>
     */
    public function mapProductImageCollection(ObjectCollection $productImageEntityCollection, SpyProductImageSet $productImageSetEntity);

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage $productImageEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function mapProductImage(SpyProductImage $productImageEntity);
}
