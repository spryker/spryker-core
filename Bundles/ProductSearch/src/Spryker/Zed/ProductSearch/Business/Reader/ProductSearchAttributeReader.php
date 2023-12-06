<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Reader;

use Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;
use Spryker\Zed\ProductSearch\Business\Expander\LocalizedProductSearchAttributeKeyExpanderInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface;

class ProductSearchAttributeReader implements ProductSearchAttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface
     */
    protected ProductSearchRepositoryInterface $productSearchRepository;

    /**
     * @var \Spryker\Zed\ProductSearch\Business\Expander\LocalizedProductSearchAttributeKeyExpanderInterface
     */
    protected LocalizedProductSearchAttributeKeyExpanderInterface $localizedProductSearchAttributeKeyExpander;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchRepositoryInterface $productSearchRepository
     * @param \Spryker\Zed\ProductSearch\Business\Expander\LocalizedProductSearchAttributeKeyExpanderInterface $localizedProductSearchAttributeKeyExpander
     */
    public function __construct(
        ProductSearchRepositoryInterface $productSearchRepository,
        LocalizedProductSearchAttributeKeyExpanderInterface $localizedProductSearchAttributeKeyExpander
    ) {
        $this->productSearchRepository = $productSearchRepository;
        $this->localizedProductSearchAttributeKeyExpander = $localizedProductSearchAttributeKeyExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeCollectionTransfer
     */
    public function getProductSearchAttributeCollection(
        ProductSearchAttributeCriteriaTransfer $productSearchAttributeCriteriaTransfer
    ): ProductSearchAttributeCollectionTransfer {
        $productSearchAttributeCollectionTransfer = $this->productSearchRepository
            ->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        $productSearchAttributeConditionsTransfer = $productSearchAttributeCriteriaTransfer->getProductSearchAttributeConditions();

        if (!$productSearchAttributeConditionsTransfer) {
            return $productSearchAttributeCollectionTransfer;
        }

        if ($productSearchAttributeConditionsTransfer->getWithLocalizedAttributes()) {
            $productSearchAttributeCollectionTransfer = $this->localizedProductSearchAttributeKeyExpander->expandProductSearchAttributeCollectionWithLocalizedKeys(
                $productSearchAttributeCollectionTransfer,
            );
        }

        return $productSearchAttributeCollectionTransfer;
    }
}
