<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Spryker\Shared\Product\Model\ProductAbstractInterface;

class ProductWriter implements ProductWriterInterface
{

    /**
     * @var AbstractProductWriterInterface
     */
    protected $productAbstractWriter;

    /**
     * @var ConcreteProductWriterInterface
     */
    protected $productWriter;

    /**
     * @param AbstractProductWriterInterface $productAbstractWriter
     * @param ConcreteProductWriterInterface $concreteProductWriter
     */
    public function __construct(
        AbstractProductWriterInterface $productAbstractWriter,
        ConcreteProductWriterInterface $concreteProductWriter
    ) {
        $this->abstractProductWriter = $productAbstractWriter;
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
        } elseif ($product instanceof AbstractProductTransfer) {
            return $this->abstractProductWriter->writeAbstractProduct($product);
        }

        return false;
    }

}
