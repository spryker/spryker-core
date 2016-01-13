<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer\Writer;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\Product\Model\ProductAbstractInterface;

class ProductWriter implements ProductWriterInterface
{

    /**
     * @var ProductAbstractWriterInterface
     */
    protected $productAbstractWriter;

    /**
     * @var ProductConcreteWriterInterface
     */
    protected $productWriter;

    /**
     * @param ProductAbstractWriterInterface $productAbstractWriter
     * @param ProductConcreteWriterInterface $productConcreteWriter
     */
    public function __construct(
        ProductAbstractWriterInterface $productAbstractWriter,
        ProductConcreteWriterInterface $productConcreteWriter
    ) {
        $this->productAbstractWriter = $productAbstractWriter;
        $this->productWriter = $productConcreteWriter;
    }

    /**
     * @param ProductAbstractInterface $product
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
