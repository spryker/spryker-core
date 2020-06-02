<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\ProductView;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface
     */
    protected $productAbstractLabelReader;

    /**
     * @param \Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface $productAbstractLabelReader
     */
    public function __construct(ProductAbstractLabelReaderInterface $productAbstractLabelReader)
    {
        $this->productAbstractLabelReader = $productAbstractLabelReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer, string $localeName, string $storeName): ProductViewTransfer
    {
        $productViewTransfer->requireIdProductAbstract();

        $productLabelDictionaryItems = $this->productAbstractLabelReader->findLabelsByIdProductAbstract(
            $productViewTransfer->getIdProductAbstract(),
            $localeName,
            $storeName
        );

        return $productViewTransfer->setLabels(new ArrayObject($productLabelDictionaryItems));
    }
}
