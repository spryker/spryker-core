<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\RestBundledProductsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class BundledProductRestResponseBuilder implements BundledProductRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteSkuNotSpecifiedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductBundlesRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setDetail(ProductBundlesRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createBundledProductEmptyRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $bundledProductRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createBundledProductCollectionRestResponse(array $bundledProductRestResources): RestResponseInterface
    {
        $bundledProductsRestResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($bundledProductRestResources as $bundledProductRestResource) {
            $bundledProductsRestResponse->addResource($bundledProductRestResource);
        }

        return $bundledProductsRestResponse;
    }

    /**
     * @param string $productConcreteSku
     * @param \Generated\Shared\Transfer\ProductBundleStorageTransfer $productBundleStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createBundledProductRestResources(
        string $productConcreteSku,
        ProductBundleStorageTransfer $productBundleStorageTransfer
    ): array {
        $bundledProductRestResources = [];
        foreach ($productBundleStorageTransfer->getBundledProducts() as $productForProductBundleStorageTransfer) {
            $restBundledProductsAttributesTransfer = (new RestBundledProductsAttributesTransfer())
                ->fromArray($productForProductBundleStorageTransfer->toArray(), true);

            $bundledProductRestResources[] = $this->restResourceBuilder->createRestResource(
                ProductBundlesRestApiConfig::RESOURCE_BUNDLED_PRODUCTS,
                $productForProductBundleStorageTransfer->getSku(),
                $restBundledProductsAttributesTransfer
            )->addLink(
                RestLinkInterface::LINK_SELF,
                sprintf(
                    '%s/%s/%s',
                    ProductBundlesRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    $productConcreteSku,
                    ProductBundlesRestApiConfig::RESOURCE_BUNDLED_PRODUCTS
                )
            );
        }

        return $bundledProductRestResources;
    }
}
