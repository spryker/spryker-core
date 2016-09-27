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
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage $productImageEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function convertProductImage(SpyProductImage $productImageEntity);

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage[]|\Propel\Runtime\Collection\ObjectCollection $productImageEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[]
     */
    public function convertProductImageCollection(ObjectCollection $productImageEntityCollection);

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function convertProductImageSet(SpyProductImageSet $productImageSetEntity);

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage[]|\Propel\Runtime\Collection\ObjectCollection $productImageSetEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function convertProductImageSetCollection(ObjectCollection $productImageSetEntityCollection);

}
