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
use Spryker\Shared\ProductAlternative\ProductAlternativeConstants;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface;

class ProductAlternativeListHydrator implements ProductAlternativeListHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface
     */
    protected $productAlternativeRepository;

    /**
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     */
    public function __construct(
        ProductAlternativeToProductFacadeInterface $productFacade,
        ProductAlternativeToLocaleFacadeInterface $localeFacade,
        ProductAlternativeRepositoryInterface $productAlternativeRepository
    ) {
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
        $this->productAlternativeRepository = $productAlternativeRepository;
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
        $productAbstractAlternative = $this->productAlternativeRepository
            ->getPreparedProductAbstractDataById(
                $idProductAbstractAlternative,
                $this->getCurrentLocale()
            );

        return $productAlternativeListItemTransfer
            ->setIdProductAlternative($productAbstractAlternative[ProductAlternativeConstants::COL_ID])
            ->setType(ProductAlternativeConstants::FIELD_PRODUCT_TYPE_ABSTRACT)
            ->setName($productAbstractAlternative[ProductAlternativeConstants::COL_NAME])
            ->setSku($productAbstractAlternative[ProductAlternativeConstants::COL_SKU])
            ->setCategories(
                explode(
                    ProductAlternativeConstants::COL_SEPARATOR_CATEGORIES,
                    $productAbstractAlternative[ProductAlternativeConstants::COL_CATEGORIES]
                )
            )
            ->setStatus($this->productFacade->isProductActive($idProductAbstractAlternative));
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
        $productConcreteAlternative = $this->productAlternativeRepository
            ->getPreparedProductConcreteDataById(
                $idProductConcreteAlternative,
                $this->getCurrentLocale()
            );

        return $productAlternativeListItemTransfer
            ->setIdProductAlternative($productConcreteAlternative[ProductAlternativeConstants::COL_ID])
            ->setType(ProductAlternativeConstants::FIELD_PRODUCT_TYPE_CONCRETE)
            ->setName($productConcreteAlternative[ProductAlternativeConstants::COL_NAME])
            ->setSku($productConcreteAlternative[ProductAlternativeConstants::COL_SKU])
            ->setCategories(
                explode(
                    ProductAlternativeConstants::COL_SEPARATOR_CATEGORIES,
                    $productConcreteAlternative[ProductAlternativeConstants::COL_CATEGORIES]
                )
            )
            ->setStatus($productConcreteAlternative[ProductAlternativeConstants::COL_STATUS]);
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
                break;
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
                break;
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
