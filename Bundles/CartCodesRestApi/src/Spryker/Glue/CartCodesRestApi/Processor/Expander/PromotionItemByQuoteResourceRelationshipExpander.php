<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestPromotionalItemsAttributesTransfer;
use Spryker\Glue\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\PromotionItemMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class PromotionItemByQuoteResourceRelationshipExpander implements PromotionItemByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartCodesRestApi\Processor\Mapper\PromotionItemMapperInterface
     */
    protected $promotionItemMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartCodesRestApi\Processor\Mapper\PromotionItemMapperInterface $promotionItemMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        PromotionItemMapperInterface $promotionItemMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->promotionItemMapper = $promotionItemMapper;
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
            $promotionItemTransfers = $this->getPromotionItemsFromPayload($resource);
            foreach ($promotionItemTransfers as $promotionItemTransfer) {
                $restPromotionalItemsAttributesTransfer = $this->promotionItemMapper
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

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PromotionItemTransfer[]
     */
    protected function getPromotionItemsFromPayload(RestResourceInterface $resource): ArrayObject
    {
        /**
         * @var \Generated\Shared\Transfer\QuoteTransfer|null $payload
         */
        $payload = $resource->getPayload();
        if ($payload === null || !($payload instanceof QuoteTransfer)) {
            return new ArrayObject();
        }

        return $payload->getPromotionItems();
    }
}
