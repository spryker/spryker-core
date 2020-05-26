<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestDiscountsAttributesTransfer;
use Spryker\Glue\CartCodesRestApi\CartCodesRestApiConfig;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\DiscountMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;

class VoucherRestResponseBuilder implements VoucherRestResponseBuilderInterface
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
     * @param \ArrayObject|\Generated\Shared\Transfer\DiscountTransfer[] $discountTransfers
     * @param string $parentResourceType
     * @param string $parentResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createVoucherRestResource(ArrayObject $discountTransfers, string $parentResourceType, string $parentResourceId): array
    {
        $voucherResources = [];
        foreach ($discountTransfers as $discountTransfer) {
            $restDiscountsAttributesTransfer = $this->discountMapper
                ->mapDiscountDataToRestDiscountsAttributesTransfer(
                    $discountTransfer,
                    new RestDiscountsAttributesTransfer()
                );

            $voucherCode = $discountTransfer->getVoucherCode();
            $voucherResource = $this->restResourceBuilder->createRestResource(
                CartCodesRestApiConfig::RESOURCE_VOUCHERS,
                $voucherCode,
                $restDiscountsAttributesTransfer
            );

            $voucherResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->getDiscountsResourceSelfLink($parentResourceType, $parentResourceId, $voucherCode)
            );

            $voucherResources[] = $voucherResource;
        }

        return $voucherResources;
    }

    /**
     * @param string $parentResourceType
     * @param string $parentResourceId
     * @param string $voucherCode
     *
     * @return string
     */
    protected function getDiscountsResourceSelfLink(
        string $parentResourceType,
        string $parentResourceId,
        string $voucherCode
    ): string {
        return sprintf(
            '%s/%s/%s/%s',
            $parentResourceType,
            $parentResourceId,
            CartCodesRestApiConfig::RESOURCE_CART_CODES,
            $voucherCode
        );
    }
}
