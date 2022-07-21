<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductLabelAggregateFormTransfer;

class ProductLabelAggregateFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider
     */
    protected $productLabelFormDataProvider;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\RelatedProductFormDataProvider
     */
    protected $relatedProductFormDataProvider;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\ProductLabelFormDataProvider $productLabelFormDataProvider
     * @param \Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider\RelatedProductFormDataProvider $relatedProductFormDataProvider
     */
    public function __construct(
        ProductLabelFormDataProvider $productLabelFormDataProvider,
        RelatedProductFormDataProvider $relatedProductFormDataProvider
    ) {
        $this->productLabelFormDataProvider = $productLabelFormDataProvider;
        $this->relatedProductFormDataProvider = $relatedProductFormDataProvider;
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelAggregateFormTransfer
     */
    public function getData($idProductLabel = null)
    {
        $aggregateFormTransfer = new ProductLabelAggregateFormTransfer();

        $productLabelTransfer = $this->productLabelFormDataProvider->getData($idProductLabel);
        $aggregateFormTransfer->setProductLabel($productLabelTransfer);

        $relationsTransfer = $this->relatedProductFormDataProvider->getData($idProductLabel);
        $aggregateFormTransfer->setProductAbstractRelations($relationsTransfer);

        return $aggregateFormTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return array_merge(
            $this->productLabelFormDataProvider->getOptions(),
            $this->relatedProductFormDataProvider->getOptions(),
        );
    }
}
