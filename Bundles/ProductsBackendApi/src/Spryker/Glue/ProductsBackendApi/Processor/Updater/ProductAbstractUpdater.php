<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ApiProductsAttributesTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductAbstractMapperInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductAbstractReaderInterface;
use Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductAbstractUpdater implements ProductAbstractUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface
     */
    protected ProductsBackendApiToProductFacadeInterface $productFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductAbstractReaderInterface
     */
    protected ProductAbstractReaderInterface $productAbstractReader;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Processor\Updater\CategoryUpdaterInterface
     */
    protected CategoryUpdaterInterface $categoryUpdater;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductAbstractMapperInterface
     */
    protected ProductAbstractMapperInterface $productAbstractMapper;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Processor\Updater\UrlUpdaterInterface
     */
    protected UrlUpdaterInterface $urlUpdater;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface $productFacade
     * @param \Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductAbstractReaderInterface $productAbstractReader
     * @param \Spryker\Glue\ProductsBackendApi\Processor\Updater\CategoryUpdaterInterface $categoryUpdater
     * @param \Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductAbstractMapperInterface $productAbstractMapper
     * @param \Spryker\Glue\ProductsBackendApi\Processor\Updater\UrlUpdaterInterface $urlUpdater
     */
    public function __construct(
        ProductsBackendApiToProductFacadeInterface $productFacade,
        ProductAbstractReaderInterface $productAbstractReader,
        CategoryUpdaterInterface $categoryUpdater,
        ProductAbstractMapperInterface $productAbstractMapper,
        UrlUpdaterInterface $urlUpdater
    ) {
        $this->productFacade = $productFacade;
        $this->productAbstractReader = $productAbstractReader;
        $this->categoryUpdater = $categoryUpdater;
        $this->productAbstractMapper = $productAbstractMapper;
        $this->urlUpdater = $urlUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateProductAbstract(
        ApiProductsAttributesTransfer $apiProductsAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        if (
            $glueRequestTransfer->getResource()
            && $glueRequestTransfer->getResource()->getId()
            && !$this->productFacade->hasProductAbstract($glueRequestTransfer->getResource()->getId())
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

        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractConditions(
                (new ProductAbstractConditionsTransfer())
                    ->setSkus([$glueRequestTransfer->getResourceOrFail()->getIdOrFail()]),
            );

        $productAbstractCollection = $this->productFacade->getProductAbstractCollection($productAbstractCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = $productAbstractCollection->getProductAbstracts()->offsetGet(0);

        $urlCollectionTransfer = $this->urlUpdater->validateUrlsOnUpdate($productAbstractTransfer->getIdProductAbstractOrFail(), $apiProductsAttributesTransfer->getUrls());
        if ($urlCollectionTransfer->getUrls()->count()) {
            return (new GlueResponseTransfer())
                ->setHttpStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setCode(ProductsBackendApiConfig::RESPONSE_CODE_PRODUCT_URL_UNUSABLE)
                        ->setMessage(ProductsBackendApiConfig::RESPONSE_DETAIL_PRODUCT_URL_UNUSABLE)
                        ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY),
                );
        }

        $productAbstractTransfer = $this->productAbstractMapper->mapApiProductsAttributesTransferToProductAbstractTransfer($apiProductsAttributesTransfer, $productAbstractTransfer);

        $productConcreteTransfers = $this->getProductConcreteTransfers($apiProductsAttributesTransfer->getVariants(), $productAbstractTransfer->getSkuOrFail());

        $idProductAbstract = $this->productFacade->saveProduct($productAbstractTransfer, $productConcreteTransfers);

        $this->urlUpdater->updateUrls($idProductAbstract, $apiProductsAttributesTransfer->getUrls());
        $this->categoryUpdater->updateCategories($apiProductsAttributesTransfer, $idProductAbstract);

        return $this->productAbstractReader->readProductAbstractCollection(
            (new ProductAbstractCriteriaTransfer())
                ->setProductAbstractConditions(
                    (new ProductAbstractConditionsTransfer())
                        ->setSkus([$productAbstractTransfer->getSkuOrFail()]),
                ),
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsProductConcreteAttributesTransfer> $apiProductsProductConcreteAttributesTransfers
     * @param string $productAbstractSku
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getProductConcreteTransfers(ArrayObject $apiProductsProductConcreteAttributesTransfers, string $productAbstractSku): array
    {
        $productConcreteTransfers = $this->productAbstractMapper->mapApiProductsProductConcreteAttributesTransfersToProductConcreteTransfers($apiProductsProductConcreteAttributesTransfers, $productAbstractSku);

        $productCriteriaTransfer = new ProductCriteriaTransfer();
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productCriteriaTransfer->addSku($productConcreteTransfer->getSkuOrFail());
        }

        $existingProductConcreteTransfers = $this->productFacade->getProductConcretesByCriteria($productCriteriaTransfer);
        $productSkuToIdProductMapping = [];
        foreach ($existingProductConcreteTransfers as $existingProductConcreteTransfer) {
            $productSkuToIdProductMapping[$existingProductConcreteTransfer->getSkuOrFail()] = $existingProductConcreteTransfer->getIdProductConcreteOrFail();
        }
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (isset($productSkuToIdProductMapping[$productConcreteTransfer->getSkuOrFail()])) {
                $productConcreteTransfer->setIdProductConcrete($productSkuToIdProductMapping[$productConcreteTransfer->getSkuOrFail()]);
            }
        }

        return $productConcreteTransfers;
    }
}
