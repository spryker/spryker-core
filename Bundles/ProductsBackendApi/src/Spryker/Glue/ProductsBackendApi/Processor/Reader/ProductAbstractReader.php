<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ApiProductsAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsCategoryAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsImageSetAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsImageSetImageAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsLocalizedAttributesAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsProductConcreteAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsProductConcreteLocalizedAttributesAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsUrlsAttributesTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductImageFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Service\ProductsBackedApiToUtilEncodingServiceInterface;
use Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductAbstractReader implements ProductAbstractReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface
     */
    protected ProductsBackendApiToProductFacadeInterface $productFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Service\ProductsBackedApiToUtilEncodingServiceInterface
     */
    protected ProductsBackedApiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface
     */
    protected ProductsBackendApiToProductCategoryFacadeInterface $productCategoryFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductImageFacadeInterface
     */
    protected ProductsBackendApiToProductImageFacadeInterface $productImageFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface
     */
    protected ProductsBackendApiToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface $productFacade
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Service\ProductsBackedApiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface $productCategoryFacade
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductImageFacadeInterface $productImageFacade
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductsBackendApiToProductFacadeInterface $productFacade,
        ProductsBackedApiToUtilEncodingServiceInterface $utilEncodingService,
        ProductsBackendApiToProductCategoryFacadeInterface $productCategoryFacade,
        ProductsBackendApiToProductImageFacadeInterface $productImageFacade,
        ProductsBackendApiToLocaleFacadeInterface $localeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->utilEncodingService = $utilEncodingService;
        $this->productCategoryFacade = $productCategoryFacade;
        $this->productImageFacade = $productImageFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getProductAbstractCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setPagination($glueRequestTransfer->getPagination())
            ->setSortCollection($glueRequestTransfer->getSortings());

        return $this->readProductAbstractCollection($productAbstractCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getProductAbstract(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractConditions(
                (new ProductAbstractConditionsTransfer())
                    ->setSkus([$glueRequestTransfer->getResourceOrFail()->getIdOrFail()]),
            );

        return $this->readProductAbstractCollection($productAbstractCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function readProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): GlueResponseTransfer
    {
        $productAbstractCriteriaTransfer->setProductAbstractRelations(
            (new ProductAbstractRelationsTransfer())
                ->setWithStoreRelations(true)
                ->setWithVariants(true)
                ->setWithTaxSet(true)
                ->setWithLocalizedAttributes(true),
        );

        $productAbstractCollectionTransfer = $this->productFacade->getProductAbstractCollection($productAbstractCriteriaTransfer);

        if (
            !$productAbstractCollectionTransfer->getProductAbstracts()->count()
            && $productAbstractCriteriaTransfer->getProductAbstractConditions()
            && $productAbstractCriteriaTransfer->getProductAbstractConditionsOrFail()->getSkus()
        ) {
            return (new GlueResponseTransfer())
                ->setHttpStatus(Response::HTTP_NOT_FOUND)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setCode(ProductsBackendApiConfig::RESPONSE_CODE_PRODUCT_NOT_FOUND)
                        ->setMessage(ProductsBackendApiConfig::RESPONSE_DETAIL_PRODUCT_NOT_FOUND)
                        ->setStatus(Response::HTTP_NOT_FOUND),
                );
        }

        $apiProductsAttributesTransfers = [];
        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $apiProductsAttributesTransfer = (new ApiProductsAttributesTransfer())
                ->fromArray($productAbstractTransfer->toArray(true, true), true)
                ->setProductAbstractSku($productAbstractTransfer->getSku())
                ->setAttributes($this->utilEncodingService->decodeJson($productAbstractTransfer->getAttributes(), true) ?? []);

            if ($productAbstractTransfer->getStoreRelation()) {
                foreach ($productAbstractTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                    $apiProductsAttributesTransfer->addStore($storeTransfer->getNameOrFail());
                }
            }

            $productCategoryCollectionTransfer = $this->productCategoryFacade->getProductCategoryCollection(
                (new ProductCategoryCriteriaTransfer())
                    ->setProductCategoryConditions(
                        (new ProductCategoryConditionsTransfer())
                            ->addIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
                    ),
            );

            foreach ($productCategoryCollectionTransfer->getProductCategories() as $productCategoryTransfer) {
                $apiProductsAttributesTransfer->addCategory(
                    (new ApiProductsCategoryAttributesTransfer())
                        ->fromArray($productCategoryTransfer->getCategoryOrFail()->toArray(), true),
                );
            }

            foreach ($productAbstractCollectionTransfer->getProductTaxSets() as $productAbstractTaxSetCollectionTransfer) {
                if ($productAbstractTaxSetCollectionTransfer->getProductAbstractSku() === $productAbstractTransfer->getSku()) {
                    $apiProductsAttributesTransfer->setTaxSetName($productAbstractTaxSetCollectionTransfer->getTaxSetOrFail()->getNameOrFail());
                }
            }

            $apiProductsAttributesTransfer = $this->mapLocalizedAttributes(
                $apiProductsAttributesTransfer,
                $productAbstractTransfer,
            );

            $productImageSetTransfers = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId($productAbstractTransfer->getIdProductAbstractOrFail());
            foreach ($productImageSetTransfers as $productImageSetTransfer) {
                $apiProductsImageSetAttributesTransfer = (new ApiProductsImageSetAttributesTransfer())
                    ->setName($productImageSetTransfer->getName());
                if ($productImageSetTransfer->getLocale()) {
                    $apiProductsImageSetAttributesTransfer->setLocale($productImageSetTransfer->getLocaleOrFail()->getLocaleNameOrFail());
                }

                foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                    $apiProductsImageSetAttributesTransfer->addImage(
                        (new ApiProductsImageSetImageAttributesTransfer())->fromArray($productImageTransfer->toArray(), true),
                    );
                }

                $apiProductsAttributesTransfer->addImageSet($apiProductsImageSetAttributesTransfer);
            }

            foreach ($productAbstractCollectionTransfer->getProductConcretes() as $productAbstractConcreteCollectionTransfer) {
                if ($productAbstractConcreteCollectionTransfer->getProductAbstractSku() === $productAbstractTransfer->getSku()) {
                    foreach ($productAbstractConcreteCollectionTransfer->getProductConcretes() as $productConcreteTransfer) {
                        $apiProductsAttributesTransfer->addVariant(
                            $this->mapVariant($productConcreteTransfer, (new ApiProductsProductConcreteAttributesTransfer())),
                        );
                    }
                }
            }

            $apiProductsAttributesTransfers[$productAbstractTransfer->getIdProductAbstract()] = $apiProductsAttributesTransfer;
        }

        $apiProductsAttributesTransfers = $this->mapUrls($apiProductsAttributesTransfers);

        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($apiProductsAttributesTransfers as $apiProductsAttributesTransfer) {
            $glueResourceTransfer = (new GlueResourceTransfer())
                ->setId($apiProductsAttributesTransfer->getProductAbstractSkuOrFail())
                ->setType(ProductsBackendApiConfig::RESOURCE_PRODUCT_ABSTRACT)
                ->setAttributes($apiProductsAttributesTransfer);

            $glueResponseTransfer->addResource($glueResourceTransfer);
        }

        $glueResponseTransfer->setPagination($productAbstractCollectionTransfer->getPagination());

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ApiProductsAttributesTransfer
     */
    public function mapLocalizedAttributes(
        ApiProductsAttributesTransfer $apiProductsAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ApiProductsAttributesTransfer {
        $apiProductsLocalizedAttributesAttributesTransfers = [];
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributeTransfer) {
            $apiProductsLocalizedAttributesAttributesTransfers[] = (new ApiProductsLocalizedAttributesAttributesTransfer())
                ->fromArray($localizedAttributeTransfer->toArray(), true)
                ->setLocale($localizedAttributeTransfer->getLocaleOrFail()->getLocaleNameOrFail());
        }

        return $apiProductsAttributesTransfer->setLocalizedAttributes(new ArrayObject($apiProductsLocalizedAttributesAttributesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ApiProductsProductConcreteAttributesTransfer $apiProductsProductConcreteAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiProductsProductConcreteAttributesTransfer
     */
    protected function mapVariant(
        ProductConcreteTransfer $productConcreteTransfer,
        ApiProductsProductConcreteAttributesTransfer $apiProductsProductConcreteAttributesTransfer
    ): ApiProductsProductConcreteAttributesTransfer {
        $apiProductsProductConcreteAttributesTransfer
            ->fromArray($productConcreteTransfer->toArray(true, true), true);

        $apiProductsProductConcreteLocalizedAttributesAttributesTransfers = [];
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributeTransfer) {
            $apiProductsProductConcreteLocalizedAttributesAttributesTransfers[] = (new ApiProductsProductConcreteLocalizedAttributesAttributesTransfer())
                ->fromArray($localizedAttributeTransfer->toArray(), true)
                ->setLocale($localizedAttributeTransfer->getLocaleOrFail()->getLocaleNameOrFail());
        }
        $apiProductsProductConcreteAttributesTransfer->setLocalizedAttributes(new ArrayObject($apiProductsProductConcreteLocalizedAttributesAttributesTransfers));

        $productImageSetTransfers = $this->productImageFacade->getProductImagesSetCollectionByProductId($productConcreteTransfer->getIdProductConcreteOrFail());
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $apiProductsImageSetAttributesTransfer = (new ApiProductsImageSetAttributesTransfer())
                ->fromArray($productImageSetTransfer->toArray(), true)
                ->setLocale($productImageSetTransfer->getLocaleOrFail()->getLocaleNameOrFail());
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $apiProductsImageSetAttributesTransfer->addImage(
                    (new ApiProductsImageSetImageAttributesTransfer())
                        ->fromArray($productImageTransfer->toArray(), true),
                );
            }

            $apiProductsProductConcreteAttributesTransfer->addImageSet($apiProductsImageSetAttributesTransfer);
        }

        return $apiProductsProductConcreteAttributesTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ApiProductsAttributesTransfer> $apiProductsAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\ApiProductsAttributesTransfer>
     */
    public function mapUrls(array $apiProductsAttributesTransfers): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $indexedLocaleTransfers = [];
        foreach ($localeTransfers as $localeTransfer) {
            $indexedLocaleTransfers[$localeTransfer->getIdLocale()] = $localeTransfer;
        }

        $urlTransfers = $this->productFacade->getProductUrls(
            (new ProductUrlCriteriaFilterTransfer())
                ->setProductAbstractIds(array_keys($apiProductsAttributesTransfers)),
        );

        foreach ($urlTransfers as $urlTransfer) {
            $apiProductsAttributesTransfers[$urlTransfer->getFkResourceProductAbstractOrFail()]->addUrl(
                (new ApiProductsUrlsAttributesTransfer())
                    ->fromArray($urlTransfer->toArray(), true)
                    ->setLocale($indexedLocaleTransfers[$urlTransfer->getFkLocale()]->getLocaleName() ?? null),
            );
        }

        return $apiProductsAttributesTransfers;
    }
}
