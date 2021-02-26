<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartItemProductConfigurationRestRequestValidator implements CartItemProductConfigurationRestRequestValidatorInterface
{
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CART_ITEMS
     */
    protected const RESOURCE_CART_ITEMS = 'items';

    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS
     */
    protected const RESOURCE_GUEST_CARTS_ITEMS = 'guest-cart-items';

    protected const VALIDATED_RESOURCE_NAMES = [
        self::RESOURCE_GUEST_CARTS_ITEMS,
        self::RESOURCE_CART_ITEMS,
    ];

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct(ProductConfigurationsRestApiToProductConfigurationStorageClientInterface $productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        if (!in_array($restRequest->getResource()->getType(), static::VALIDATED_RESOURCE_NAMES, true)) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer */
        $restCartItemsAttributesTransfer = $restRequest->getResource()->getAttributes();
        $restCartItemProductConfigurationInstanceAttributesTransfer = $restCartItemsAttributesTransfer->getProductConfigurationInstance();
        if (!$restCartItemProductConfigurationInstanceAttributesTransfer) {
            return null;
        }

        $productConcreteSku = $this->resolveProductConcreteSku($restCartItemsAttributesTransfer, $restRequest);
        if (!$productConcreteSku) {
            return null;
        }

        $productConfigurationInstanceTransfer = $this->productConfigurationStorageClient->findProductConfigurationInstanceBySku($productConcreteSku);

        if (
            $productConfigurationInstanceTransfer
            && $productConfigurationInstanceTransfer->getConfiguratorKey() === $restCartItemProductConfigurationInstanceAttributesTransfer->getConfiguratorKey()
        ) {
            return null;
        }

        return (new RestErrorCollectionTransfer())
            ->addRestError($this->createErrorMessageTransfer(
                $productConcreteSku,
                $restCartItemProductConfigurationInstanceAttributesTransfer->getConfiguratorKey()
            ));
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function resolveProductConcreteSku(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer, RestRequestInterface $restRequest): ?string
    {
        return $restCartItemsAttributesTransfer->getSku() ?? $restRequest->getResource()->getId();
    }

    /**
     * @param string $sku
     * @param string|null $productConfigurationInstanceKey
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(string $sku, ?string $productConfigurationInstanceKey): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ProductConfigurationsRestApiConfig::RESPONSE_CODE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(sprintf(
                ProductConfigurationsRestApiConfig::ERROR_MESSAGE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING,
                $sku,
                $productConfigurationInstanceKey
            ));
    }
}
