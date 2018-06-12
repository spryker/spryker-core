<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface;

class ProductAlternativeListHydrator implements ProductAlternativeListHydratorInterface
{
    protected const COL_NAME = 'name';
    protected const COL_SKU = 'sku';
    protected const COL_STATUS = 'status';
    protected const COL_CATEGORY = 'category';

    protected const FIELD_PRODUCT_TYPE_ABSTRACT = 'Abstract';
    protected const FIELD_PRODUCT_TYPE_CONCRETE = 'Concrete';

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductAlternativeToProductFacadeInterface $productFacade,
        ProductAlternativeToLocaleFacadeInterface $localeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function hydrateProductAbstractListItem(
        int $idProductAbstractAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productAlternativeListItemTransfer = $this->hydrateListItemWithProductAbstractAlternativeData(
            $idProductAbstractAlternative,
            $productAlternativeListItemTransfer
        );

        return $productAlternativeListItemTransfer;
    }

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function hydrateProductConcreteListItem(
        int $idProductConcreteAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productAlternativeListItemTransfer = $this->hydrateListItemWithProductConcreteAlternativeData(
            $idProductConcreteAlternative,
            $productAlternativeListItemTransfer
        );

        return $productAlternativeListItemTransfer;
    }

    /**
     * @param int $idProductAbstractAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateListItemWithProductAbstractAlternativeData(
        int $idProductAbstractAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productAbstractAlternative = $this->productFacade
            ->findProductAbstractById($idProductAbstractAlternative);

        if (!$productAbstractAlternative) {
            return $productAlternativeListItemTransfer;
        }

        return $productAlternativeListItemTransfer
            ->setType(static::FIELD_PRODUCT_TYPE_ABSTRACT)
            ->setName(
                $this->getProductAbstractName($productAbstractAlternative)
            )
            ->setSku($productAbstractAlternative->getSku())
            ->setStatus($productAbstractAlternative->getIsActive()); // TODO: Add ProductFacade call.
    }

    /**
     * @param int $idProductConcreteAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateListItemWithProductConcreteAlternativeData(
        int $idProductConcreteAlternative,
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
    ): ProductAlternativeListItemTransfer {
        $productConcreteAlternative = $this->productFacade
            ->findProductConcreteById($idProductConcreteAlternative);

        if (!$productConcreteAlternative) {
            return $productAlternativeListItemTransfer;
        }

        return $productAlternativeListItemTransfer
            ->setType(static::FIELD_PRODUCT_TYPE_CONCRETE)
            ->setName(
                $this->getProductConcreteName($productConcreteAlternative)
            )
            ->setSku($productConcreteAlternative->getSku())
            ->setStatus($productConcreteAlternative->getIsActive());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return null|string
     */
    protected function getProductAbstractName(ProductAbstractTransfer $productAbstractTransfer): ?string
    {
        $idCurrentLocale = $this->getCurrentLocale()->getIdLocale();

        $productName = '';
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getIdLocale() === $idCurrentLocale) {
                $productName = $localizedAttribute->getName();
            }
        }

        return $productName;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return null|string
     */
    protected function getProductConcreteName(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $idCurrentLocale = $this->getCurrentLocale()->getIdLocale();

        $productName = '';
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getIdLocale() === $idCurrentLocale) {
                $productName = $localizedAttribute->getName();
            }
        }

        return $productName;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
    }
}
