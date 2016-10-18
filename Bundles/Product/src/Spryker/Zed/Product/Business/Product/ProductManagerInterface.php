<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Exception
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array|\Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Exception
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributeProcessor($idProductAbstract);

    /**
     * @param string $abstractSku
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributeProcessorByAbstractSku($abstractSku);

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract);

}
