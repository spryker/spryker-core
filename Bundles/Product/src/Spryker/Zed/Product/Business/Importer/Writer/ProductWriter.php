<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductWriter implements ProductWriterInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\Importer\Writer\ProductAbstractWriterInterface
     */
    protected $productAbstractWriter;

    /**
     * @var \Spryker\Zed\Product\Business\Importer\Writer\ProductConcreteWriterInterface
     */
    protected $productWriter;

    /**
     * @param \Spryker\Zed\Product\Business\Importer\Writer\ProductAbstractWriterInterface $productAbstractWriter
     * @param \Spryker\Zed\Product\Business\Importer\Writer\ProductConcreteWriterInterface $productConcreteWriter
     */
    public function __construct(
        ProductAbstractWriterInterface $productAbstractWriter,
        ProductConcreteWriterInterface $productConcreteWriter
    ) {
        $this->productAbstractWriter = $productAbstractWriter;
        $this->productWriter = $productConcreteWriter;
    }

    /**
     * @param \Spryker\Shared\Product\Model\ProductAbstractInterface $product
     *
     * @return bool
     */
    public function writeProduct($product)
    {
        if ($product instanceof ProductConcreteTransfer) {
            return $this->productWriter->writeProduct($product);
        } elseif ($product instanceof ProductAbstractTransfer) {
            return $this->productAbstractWriter->writeProductAbstract($product);
        }

        return false;
    }

}
