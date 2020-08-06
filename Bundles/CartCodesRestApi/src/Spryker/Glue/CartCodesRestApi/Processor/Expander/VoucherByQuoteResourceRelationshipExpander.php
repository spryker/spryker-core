<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\VoucherRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class VoucherByQuoteResourceRelationshipExpander implements VoucherByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\VoucherRestResponseBuilderInterface
     */
    protected $voucherRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\VoucherRestResponseBuilderInterface $voucherRestResponseBuilder
     */
    public function __construct(VoucherRestResponseBuilderInterface $voucherRestResponseBuilder)
    {
        $this->voucherRestResponseBuilder = $voucherRestResponseBuilder;
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

            $discountTransfers = $payload->getVoucherDiscounts();
            if (!count($discountTransfers)) {
                continue;
            }

            $voucherRestResources = $this->voucherRestResponseBuilder
                ->createVoucherRestResource($discountTransfers, $resource->getType(), $payload->getUuid());

            $this->addVoucherResourceRelationship($resource, $voucherRestResources);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $voucherRestResources
     *
     * @return void
     */
    protected function addVoucherResourceRelationship(RestResourceInterface $resource, array $voucherRestResources): void
    {
        foreach ($voucherRestResources as $voucherRestResource) {
            $resource->addRelationship($voucherRestResource);
        }
    }
}
