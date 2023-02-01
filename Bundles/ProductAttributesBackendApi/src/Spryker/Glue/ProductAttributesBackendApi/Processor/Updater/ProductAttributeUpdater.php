<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface;

class ProductAttributeUpdater implements ProductAttributeUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface
     */
    protected ProductAttributesBackendApiToProductAttributeFacadeInterface $productAttributeFacade;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface
     */
    protected ProductAttributeRestResponseBuilderInterface $productAttributeRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface
     */
    protected ProductAttributeMapperInterface $productAttributeMapper;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface
     */
    protected ProductAttributeReaderInterface $productAttributeReader;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface
     */
    protected ProductAttributeExpanderInterface $productAttributeExpander;

    /**
     * @param \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface $productAttributeFacade
     * @param \Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface $productAttributeRestResponseBuilder
     * @param \Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface $productAttributeMapper
     * @param \Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface $productAttributeReader
     * @param \Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface $productAttributeExpander
     */
    public function __construct(
        ProductAttributesBackendApiToProductAttributeFacadeInterface $productAttributeFacade,
        ProductAttributeRestResponseBuilderInterface $productAttributeRestResponseBuilder,
        ProductAttributeMapperInterface $productAttributeMapper,
        ProductAttributeReaderInterface $productAttributeReader,
        ProductAttributeExpanderInterface $productAttributeExpander
    ) {
        $this->productAttributeFacade = $productAttributeFacade;
        $this->productAttributeRestResponseBuilder = $productAttributeRestResponseBuilder;
        $this->productAttributeMapper = $productAttributeMapper;
        $this->productAttributeReader = $productAttributeReader;
        $this->productAttributeExpander = $productAttributeExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateProductAttribute(
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        if (!$glueRequestTransfer->getResource() || !$glueRequestTransfer->getResource()->getId()) {
            return $this->productAttributeRestResponseBuilder->createProductAttributeKeyIsNotProvidedErrorRestResponse();
        }

        $productAttributeKey = $glueRequestTransfer->getResource()->getId();
        $productManagementAttributeTransfer = $this->productAttributeReader->findProductAttributeByKey($productAttributeKey);

        if (!$productManagementAttributeTransfer) {
            return $this->productAttributeRestResponseBuilder->createProductAttributeNotFoundErrorRestResponse();
        }

        $productManagementAttributeTransfer = $this->productAttributeMapper->mapRestProductAttributesBackendAttributesTransferToProductManagementAttributeTransfer(
            $restProductAttributesBackendAttributesTransfer,
            $productManagementAttributeTransfer,
        );
        $productManagementAttributeTransfer->setKey($productAttributeKey);
        $this->productAttributeExpander->expandProductManagementAttributeValueTransfersWithLocaleName($productManagementAttributeTransfer->getValues());

        $productManagementAttributeTransfer = $this->productAttributeFacade->updateProductManagementAttribute($productManagementAttributeTransfer);
        $this->productAttributeFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
        /** @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer */
        $productManagementAttributeTransfer = $this->productAttributeReader->findProductAttributeByKey($productAttributeKey);

        return $this->productAttributeRestResponseBuilder->createProductAttributesRestResponse($productManagementAttributeTransfer);
    }
}
