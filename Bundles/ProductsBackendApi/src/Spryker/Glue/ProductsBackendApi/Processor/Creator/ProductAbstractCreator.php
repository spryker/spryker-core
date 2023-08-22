<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductAbstractMapperInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductAbstractReaderInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\CategoryUpdaterInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\UrlUpdaterInterface;
use Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductAbstractCreator implements ProductAbstractCreatorInterface
{
    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

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
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAbstract(
        ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        if (
            $productsBackendApiAttributesTransfer->getProductAbstractSku()
            && $this->productFacade->hasProductAbstract($productsBackendApiAttributesTransfer->getProductAbstractSku())
        ) {
            return (new GlueResponseTransfer())
                ->setHttpStatus(Response::HTTP_CONFLICT)
                ->addError(
                    (new GlueErrorTransfer())
                        ->setCode(ProductsBackendApiConfig::RESPONSE_CODE_PRODUCT_EXISTS)
                        ->setMessage(ProductsBackendApiConfig::RESPONSE_DETAIL_PRODUCT_EXISTS)
                        ->setStatus(Response::HTTP_CONFLICT),
                );
        }

        $urlCollectionTransfer = $this->urlUpdater->validateUrlsOnCreate($productsBackendApiAttributesTransfer->getUrls());
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

        $productAbstractTransfer = $this->productAbstractMapper
            ->mapProductsBackendApiAttributesTransferToProductAbstractTransfer($productsBackendApiAttributesTransfer, new ProductAbstractTransfer());
        $productConcreteTransfers = $this->productAbstractMapper
            ->mapProductConcretesBackendApiAttributesTransfersToProductConcreteTransfers($productsBackendApiAttributesTransfer->getVariants(), $productsBackendApiAttributesTransfer->getProductAbstractSkuOrFail());

        $productAbstractTransfer->setApprovalStatus(static::STATUS_APPROVED);

        $idProductAbstract = $this->productFacade->addProduct($productAbstractTransfer, $productConcreteTransfers);

        $this->urlUpdater->createUrls($idProductAbstract, $productsBackendApiAttributesTransfer->getUrls());
        $this->categoryUpdater->createCategoryAssignment($productsBackendApiAttributesTransfer, $idProductAbstract);

        return $this->productAbstractReader->readProductAbstractCollection(
            (new ProductAbstractCriteriaTransfer())
                ->setProductAbstractConditions(
                    (new ProductAbstractConditionsTransfer())
                        ->setSkus([$productAbstractTransfer->getSkuOrFail()]),
                ),
        );
    }
}
