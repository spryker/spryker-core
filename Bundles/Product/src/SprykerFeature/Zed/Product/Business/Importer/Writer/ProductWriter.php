<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Writer;

use SprykerFeature\Zed\Product\Business\Importer\Model\AbstractProduct;
use SprykerFeature\Zed\Product\Business\Importer\Model\ConcreteProduct;
use SprykerFeature\Shared\Product\Model\AbstractProductInterface;

/**
 * Class GeneraWriter
 *
 * @package Zed\Product\Component\Importer\Writer
 */
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
    public function writeProduct(AbstractProductInterface $product)
    {
        if ($product instanceof ConcreteProduct) {
            return $this->productWriter->writeProduct($product);
        } elseif ($product instanceof AbstractProduct) {
            return $this->abstractProductWriter->writeAbstractProduct($product);
        }

        return false;
    }
}
 