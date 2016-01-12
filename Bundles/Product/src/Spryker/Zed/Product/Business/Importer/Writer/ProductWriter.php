<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Spryker\Shared\Product\Model\ProductAbstractInterface;

class ProductWriter implements ProductWriterInterface
{

    /**
     * @var ProductAbstractWriterInterface
     */
    protected $productAbstractWriter;

    /**
     * @var ConcreteProductWriterInterface
     */
    protected $productWriter;

    /**
     * @param ProductAbstractWriterInterface $productAbstractWriter
     * @param ConcreteProductWriterInterface $concreteProductWriter
     */
    public function __construct(
        ProductAbstractWriterInterface $productAbstractWriter,
        ConcreteProductWriterInterface $concreteProductWriter
    ) {
        $this->productAbstractWriter = $productAbstractWriter;
        $this->productWriter = $concreteProductWriter;
    }

    /**
     * @param ProductAbstractInterface $product
     *
     * @return bool
     */
    public function writeProduct($product)
    {
        if ($product instanceof ConcreteProductTransfer) {
            return $this->productWriter->writeProduct($product);
        } elseif ($product instanceof ProductAbstractTransfer) {
            return $this->productAbstractWriter->writeProductAbstract($product);
        }

        return false;
    }

}
