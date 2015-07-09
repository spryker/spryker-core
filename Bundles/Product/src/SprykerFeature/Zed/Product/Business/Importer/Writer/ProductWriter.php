<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use SprykerFeature\Shared\Product\Model\AbstractProductInterface;

class ProductWriter implements ProductWriterInterface
{

    /**
     * @var AbstractProductWriterInterface
     */
    protected $abstractProductWriter;

    /**
     * @var ConcreteProductWriterInterface
     */
    protected $productWriter;

    /**
     * @param AbstractProductWriterInterface $abstractProductWriter
     * @param ConcreteProductWriterInterface $concreteProductWriter
     */
    public function __construct(
        AbstractProductWriterInterface $abstractProductWriter,
        ConcreteProductWriterInterface $concreteProductWriter
    ) {
        $this->abstractProductWriter = $abstractProductWriter;
        $this->productWriter = $concreteProductWriter;
    }

    /**
     * @param AbstractProductInterface $product
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
