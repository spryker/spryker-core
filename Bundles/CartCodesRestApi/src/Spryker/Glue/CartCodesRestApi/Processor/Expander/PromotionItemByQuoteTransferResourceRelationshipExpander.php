<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestPromotionalItemsAttributesTransfer;
use Spryker\Glue\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PromotionItemByQuoteTransferResourceRelationshipExpander implements PromotionItemByQuoteTransferResourceRelationshipExpanderInterface
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

            $promotionalItemTransfers = $payload->getPromotionItems();

            foreach ($promotionalItemTransfers as $promotionItemTransfer) {
                $restPromotionalItemsAttributesTransfer = $this->discountMapper
                    ->mapPromotionItemTransferToRestPromotionalItemsAttributesTransfer(
                        $promotionItemTransfer,
                        new RestPromotionalItemsAttributesTransfer()
                    );

                $promotionalItemsResource = $this->restResourceBuilder->createRestResource(
                    CartCodesRestApiConfig::RESOURCE_PROMOTIONAL_ITEMS,
                    (string)$promotionItemTransfer->getIdDiscountPromotion(),
                    $restPromotionalItemsAttributesTransfer
                );

                $resource->addRelationship($promotionalItemsResource);
            }
        }
    }
}
