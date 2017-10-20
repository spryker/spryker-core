<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\ProductSet\Business\Exception\ProductSetNotFoundException;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetEntityReader implements ProductSetEntityReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     */
    public function __construct(ProductSetQueryContainerInterface $productSetQueryContainer)
    {
        $this->productSetQueryContainer = $productSetQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @throws \Spryker\Zed\ProductSet\Business\Exception\ProductSetNotFoundException
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSet
     */
    public function getProductSetEntity(ProductSetTransfer $productSetTransfer)
    {
        $this->assertProductSetForRead($productSetTransfer);

        $productSetEntity = $this->productSetQueryContainer
            ->queryProductSetById($productSetTransfer->getIdProductSet())
            ->findOne();

        if (!$productSetEntity) {
            throw new ProductSetNotFoundException(sprintf(
                'Product set with ID "%d" not found.',
                $productSetTransfer->getIdProductSet()
            ));
        }

        return $productSetEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForRead(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer->requireIdProductSet();
    }
}
