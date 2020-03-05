<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductListTableDataProvider;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductListTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver\MerchantUserResolverInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface;

class ProductListTableDataProvider implements ProductListTableDataProviderInterface
{
    protected const EXTENDED_PRODUCT_CONCRETE_NAME_SEPARATOR = ', ';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface
     */
    protected $ProductOfferGuiPageRepository;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver\MerchantUserResolverInterface
     */
    protected $merchantUserResolver;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductImageFacadeInterface $productImageFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver\MerchantUserResolverInterface $merchantUserResolver
     * @param \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface $ProductOfferGuiPageRepository
     */
    public function __construct(
        ProductOfferGuiPageToProductImageFacadeInterface $productImageFacade,
        ProductOfferGuiPageToLocaleFacadeInterface $localeFacade,
        MerchantUserResolverInterface $merchantUserResolver,
        ProductOfferGuiPageRepositoryInterface $ProductOfferGuiPageRepository
    ) {

        $this->productImageFacade = $productImageFacade;
        $this->localeFacade = $localeFacade;
        $this->merchantUserResolver = $merchantUserResolver;
        $this->ProductOfferGuiPageRepository = $ProductOfferGuiPageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductListTableData(ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): ProductConcreteCollectionTransfer
    {
        $productConcreteCollectionTransfer = $this->ProductOfferGuiPageRepository
            ->getConcreteProductsForProductListTable(
                $productListTableCriteriaTransfer,
                $this->localeFacade->getCurrentLocale(),
                $this->merchantUserResolver->findCurrentMerchantUser()
            );
        $productConcreteCollectionTransfer = $this->addProductImagesToConcreteProducts($productConcreteCollectionTransfer);
        $productConcreteCollectionTransfer = $this->transformProductConcreteNames($productConcreteCollectionTransfer);

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    protected function addProductImagesToConcreteProducts(ProductConcreteCollectionTransfer $productConcreteCollectionTransfer): ProductConcreteCollectionTransfer
    {
        $productImageSetCollectionTransfer = $this->findProductConcreteProductImageSetsForCurrentLocale($productConcreteCollectionTransfer);
        $productConcreteCollectionTransfer = $this->mergeConcreteProductsWithProductImageSets(
            $productConcreteCollectionTransfer,
            $productImageSetCollectionTransfer
        );

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function findProductConcreteProductImageSetsForCurrentLocale(ProductConcreteCollectionTransfer $productConcreteCollectionTransfer): array
    {
        $productConcreteIds = $this->extractProductConcreteIds($productConcreteCollectionTransfer);
        $localeId = $this->localeFacade->getCurrentLocale()->requireIdLocale()->getIdLocale();

        return $this->productImageFacade->getProductImageSetsByProductConcreteIdsAndLocaleId($productConcreteIds, $localeId);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    protected function mergeConcreteProductsWithProductImageSets(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        array $productImageSetTransfers
    ): ProductConcreteCollectionTransfer {
        foreach ($productConcreteCollectionTransfer->getConcreteProducts() as $productConcreteTransfer) {
            foreach ($productImageSetTransfers as $productImageSetTransfer) {
                if ($productConcreteTransfer->getIdProduct() === $productImageSetTransfer->getIdProduct()) {
                    $productConcreteTransfer->addImageSet($productImageSetTransfer);

                    continue;
                }
            }
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteDataCollectionTransfer
     *
     * @return int[]
     */
    protected function extractProductConcreteIds(ProductConcreteCollectionTransfer $productConcreteDataCollectionTransfer): array
    {
        $productConcreteIds = array_map(function (ProductConcreteTransfer $productConcreteTransfer) {
            return $productConcreteTransfer->getIdProduct() ?? null;
        }, $productConcreteDataCollectionTransfer->getConcreteProducts()->getArrayCopy());

        $productConcreteIds = array_filter($productConcreteIds);

        return $productConcreteIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    protected function transformProductConcreteNames(ProductConcreteCollectionTransfer $productConcreteCollectionTransfer): ProductConcreteCollectionTransfer
    {
        foreach ($productConcreteCollectionTransfer->getConcreteProducts() as $productConcreteTransfer) {
            $extendedProductConcreteName = $this->buildExtendedProductConcreteName($productConcreteTransfer);
            $productConcreteTransfer->setName($extendedProductConcreteName);
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    protected function buildExtendedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $productConcreteName = $productConcreteTransfer->getName();

        if (!$productConcreteName) {
            return null;
        }

        $extendedProductConcreteNameParts = [$productConcreteName];

        foreach ($productConcreteTransfer->getAttributes() as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
        }

        return implode(static::EXTENDED_PRODUCT_CONCRETE_NAME_SEPARATOR, $extendedProductConcreteNameParts);
    }
}
