<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestDiscountsAttributesTransfer;
use Spryker\Glue\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartRuleByQuoteResourceRelationshipExpander implements CartRuleByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapperInterface
     */
    protected $discountMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapperInterface $discountMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        DiscountMapperInterface $discountMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->discountMapper = $discountMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\QuoteTransfer|null $payload
             */
            $payload = $resource->getPayload();
            if ($payload === null || !($payload instanceof QuoteTransfer)) {
                continue;
            }

            $discountTransfers = $payload->getCartRuleDiscounts();
            if (!count($discountTransfers)) {
                continue;
            }

            $this->addDiscountResourceRelationship($discountTransfers, $resource);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\DiscountTransfer[] $discountTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addDiscountResourceRelationship(
        ArrayObject $discountTransfers,
        RestResourceInterface $resource
    ): void {
        foreach ($discountTransfers as $discountTransfer) {
            $restDiscountsAttributesTransfer = $this->discountMapper
                ->mapDiscountDataToRestDiscountsAttributesTransfer(
                    $discountTransfer,
                    new RestDiscountsAttributesTransfer()
                );

            $discountResource = $this->restResourceBuilder->createRestResource(
                CartCodesRestApiConfig::RESOURCE_CART_RULES,
                (string)$discountTransfer->getIdDiscount(),
                $restDiscountsAttributesTransfer
            );

            $resource->addRelationship($discountResource);
        }
    }
}
