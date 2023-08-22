<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductCategoriesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteLocalizedAttributesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductImageSetImagesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductLocalizedAttributesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer;
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

        $productsBackendApiAttributesTransfers = [];
        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $productsBackendApiAttributesTransfer = (new ProductsBackendApiAttributesTransfer())
                ->fromArray($productAbstractTransfer->toArray(true, true), true)
                ->setProductAbstractSku($productAbstractTransfer->getSku())
                ->setAttributes($this->utilEncodingService->decodeJson($productAbstractTransfer->getAttributes(), true) ?? []);

            if ($productAbstractTransfer->getStoreRelation()) {
                foreach ($productAbstractTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                    $productsBackendApiAttributesTransfer->addStore($storeTransfer->getNameOrFail());
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
                $productsBackendApiAttributesTransfer->addCategory(
                    (new ProductCategoriesBackendApiAttributesTransfer())
                        ->fromArray($productCategoryTransfer->getCategoryOrFail()->toArray(), true),
                );
            }

            foreach ($productAbstractCollectionTransfer->getProductTaxSets() as $productAbstractTaxSetCollectionTransfer) {
                if ($productAbstractTaxSetCollectionTransfer->getProductAbstractSku() === $productAbstractTransfer->getSku()) {
                    $productsBackendApiAttributesTransfer->setTaxSetName($productAbstractTaxSetCollectionTransfer->getTaxSetOrFail()->getNameOrFail());
                }
            }

            $productsBackendApiAttributesTransfer = $this->mapLocalizedAttributes(
                $productsBackendApiAttributesTransfer,
                $productAbstractTransfer,
            );

            $productImageSetTransfers = $this->productImageFacade->getProductImagesSetCollectionByProductAbstractId($productAbstractTransfer->getIdProductAbstractOrFail());
            foreach ($productImageSetTransfers as $productImageSetTransfer) {
                $productImageSetBackendApiAttributesTransfer = (new ProductImageSetBackendApiAttributesTransfer())
                    ->setName($productImageSetTransfer->getName());
                if ($productImageSetTransfer->getLocale()) {
                    $productImageSetBackendApiAttributesTransfer->setLocale($productImageSetTransfer->getLocaleOrFail()->getLocaleNameOrFail());
                }

                foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                    $productImageSetBackendApiAttributesTransfer->addImage(
                        (new ProductImageSetImagesBackendApiAttributesTransfer())->fromArray($productImageTransfer->toArray(), true),
                    );
                }

                $productsBackendApiAttributesTransfer->addImageSet($productImageSetBackendApiAttributesTransfer);
            }

            foreach ($productAbstractCollectionTransfer->getProductConcretes() as $productAbstractConcreteCollectionTransfer) {
                if ($productAbstractConcreteCollectionTransfer->getProductAbstractSku() === $productAbstractTransfer->getSku()) {
                    foreach ($productAbstractConcreteCollectionTransfer->getProductConcretes() as $productConcreteTransfer) {
                        $productsBackendApiAttributesTransfer->addVariant(
                            $this->mapVariant($productConcreteTransfer, (new ProductConcretesBackendApiAttributesTransfer())),
                        );
                    }
                }
            }

            $productsBackendApiAttributesTransfers[$productAbstractTransfer->getIdProductAbstract()] = $productsBackendApiAttributesTransfer;
        }

        $productsBackendApiAttributesTransfers = $this->mapUrls($productsBackendApiAttributesTransfers);

        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($productsBackendApiAttributesTransfers as $productsBackendApiAttributesTransfer) {
            $glueResourceTransfer = (new GlueResourceTransfer())
                ->setId($productsBackendApiAttributesTransfer->getProductAbstractSkuOrFail())
                ->setType(ProductsBackendApiConfig::RESOURCE_PRODUCT_ABSTRACT)
                ->setAttributes($productsBackendApiAttributesTransfer);

            $glueResponseTransfer->addResource($glueResourceTransfer);
        }

        $glueResponseTransfer->setPagination($productAbstractCollectionTransfer->getPagination());

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer
     */
    public function mapLocalizedAttributes(
        ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductsBackendApiAttributesTransfer {
        $productLocalizedAttributesBackendApiAttributesTransfers = [];
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributeTransfer) {
            $productLocalizedAttributesBackendApiAttributesTransfers[] = (new ProductLocalizedAttributesBackendApiAttributesTransfer())
                ->fromArray($localizedAttributeTransfer->toArray(), true)
                ->setLocale($localizedAttributeTransfer->getLocaleOrFail()->getLocaleNameOrFail());
        }

        return $productsBackendApiAttributesTransfer->setLocalizedAttributes(new ArrayObject($productLocalizedAttributesBackendApiAttributesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer $productConcretesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer
     */
    protected function mapVariant(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcretesBackendApiAttributesTransfer $productConcretesBackendApiAttributesTransfer
    ): ProductConcretesBackendApiAttributesTransfer {
        $productConcretesBackendApiAttributesTransfer
            ->fromArray($productConcreteTransfer->toArray(true, true), true);

        $productConcreteLocalizedAttributesBackendApiAttributesTransfers = [];
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributeTransfer) {
            $productConcreteLocalizedAttributesBackendApiAttributesTransfers[] = (new ProductConcreteLocalizedAttributesBackendApiAttributesTransfer())
                ->fromArray($localizedAttributeTransfer->toArray(), true)
                ->setLocale($localizedAttributeTransfer->getLocaleOrFail()->getLocaleNameOrFail());
        }
        $productConcretesBackendApiAttributesTransfer->setLocalizedAttributes(new ArrayObject($productConcreteLocalizedAttributesBackendApiAttributesTransfers));

        $productImageSetTransfers = $this->productImageFacade->getProductImagesSetCollectionByProductId($productConcreteTransfer->getIdProductConcreteOrFail());
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $productImageSetBackendApiAttributesTransfer = (new ProductImageSetBackendApiAttributesTransfer())
                ->fromArray($productImageSetTransfer->toArray(), true)
                ->setLocale($productImageSetTransfer->getLocaleOrFail()->getLocaleNameOrFail());
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $productImageSetBackendApiAttributesTransfer->addImage(
                    (new ProductImageSetImagesBackendApiAttributesTransfer())
                        ->fromArray($productImageTransfer->toArray(), true),
                );
            }

            $productConcretesBackendApiAttributesTransfer->addImageSet($productImageSetBackendApiAttributesTransfer);
        }

        return $productConcretesBackendApiAttributesTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer> $productsBackendApiAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer>
     */
    public function mapUrls(array $productsBackendApiAttributesTransfers): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $indexedLocaleTransfers = [];
        foreach ($localeTransfers as $localeTransfer) {
            $indexedLocaleTransfers[$localeTransfer->getIdLocale()] = $localeTransfer;
        }

        $urlTransfers = $this->productFacade->getProductUrls(
            (new ProductUrlCriteriaFilterTransfer())
                ->setProductAbstractIds(array_keys($productsBackendApiAttributesTransfers)),
        );

        foreach ($urlTransfers as $urlTransfer) {
            $productsBackendApiAttributesTransfers[$urlTransfer->getFkResourceProductAbstractOrFail()]->addUrl(
                (new ProductUrlsBackendApiAttributesTransfer())
                    ->fromArray($urlTransfer->toArray(), true)
                    ->setLocale($indexedLocaleTransfers[$urlTransfer->getFkLocale()]->getLocaleName() ?? null),
            );
        }

        return $productsBackendApiAttributesTransfers;
    }
}
