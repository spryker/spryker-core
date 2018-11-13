<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestCartsDiscountsTransfer;
use Generated\Shared\Transfer\RestCartsTotalsTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartsResourceMapper implements CartsResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CartItemsResourceMapperInterface $cartItemsResourceMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapCartsResource(QuoteTransfer $quoteTransfer, RestRequestInterface $restRequest): RestResourceInterface
    {
        $restCartsAttributesTransfer = new RestCartsAttributesTransfer();

        $this->setBaseCartData($quoteTransfer, $restCartsAttributesTransfer);
        $this->setTotals($quoteTransfer, $restCartsAttributesTransfer);
        $this->setDiscounts($quoteTransfer, $restCartsAttributesTransfer);

        $cartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_CARTS,
            $quoteTransfer->getUuid(),
            $restCartsAttributesTransfer
        );
        $this->mapCartItems($quoteTransfer, $cartResource);

        return $cartResource;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartsAttributesTransfer
     */
    public function mapQuoteTransferToRestCartsAttributesTransfer(QuoteTransfer $quoteTransfer): RestCartsAttributesTransfer
    {
        $restCartsAttributesTransfer = new RestCartsAttributesTransfer();

        $this->setBaseCartData($quoteTransfer, $restCartsAttributesTransfer);
        $this->setTotals($quoteTransfer, $restCartsAttributesTransfer);
        $this->setDiscounts($quoteTransfer, $restCartsAttributesTransfer);

        return $restCartsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartResource
     *
     * @return void
     */
    protected function mapCartItems(QuoteTransfer $quoteTransfer, RestResourceInterface $cartResource): void
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemResource = $this->restResourceBuilder->createRestResource(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $itemTransfer->getGroupKey(),
                $this->cartItemsResourceMapper->mapCartItemAttributes($itemTransfer)
            );
            $itemResource->addLink(
                RestLinkInterface::LINK_SELF,
                CartsRestApiConfig::RESOURCE_CARTS . '/' . $cartResource->getId() . '/' . CartsRestApiConfig::RESOURCE_CART_ITEMS . '/' . $itemTransfer->getGroupKey()
            );

            $cartResource->addRelationship($itemResource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return void
     */
    protected function setDiscounts(QuoteTransfer $quoteTransfer, RestCartsAttributesTransfer $restCartsAttributesTransfer): void
    {
        foreach ($quoteTransfer->getVoucherDiscounts() as $discountTransfer) {
            $restCartsDiscounts = new RestCartsDiscountsTransfer();
            $restCartsDiscounts->fromArray($discountTransfer->toArray(), true);
            $restCartsAttributesTransfer->addDiscount($restCartsDiscounts);
        }

        foreach ($quoteTransfer->getCartRuleDiscounts() as $discountTransfer) {
            $restCartsDiscounts = new RestCartsDiscountsTransfer();
            $restCartsDiscounts->fromArray($discountTransfer->toArray(), true);
            $restCartsAttributesTransfer->addDiscount($restCartsDiscounts);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return void
     */
    protected function setTotals(QuoteTransfer $quoteTransfer, RestCartsAttributesTransfer $restCartsAttributesTransfer): void
    {
        if ($quoteTransfer->getTotals() === null) {
            $restCartsAttributesTransfer->setTotals(new RestCartsTotalsTransfer());
            return;
        }

        $cartsTotalsTransfer = (new RestCartsTotalsTransfer())
            ->fromArray($quoteTransfer->getTotals()->toArray(), true);

        $taxTotalTransfer = $quoteTransfer->getTotals()->getTaxTotal();
        if (!empty($taxTotalTransfer)) {
            $cartsTotalsTransfer->setTaxTotal($taxTotalTransfer->getAmount());
        }

        $restCartsAttributesTransfer->setTotals($cartsTotalsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return void
     */
    protected function setBaseCartData(
        QuoteTransfer $quoteTransfer,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): void {
        $restCartsAttributesTransfer->fromArray($quoteTransfer->toArray(), true);

        $restCartsAttributesTransfer
            ->setCurrency($quoteTransfer->getCurrency()->getCode())
            ->setStore($quoteTransfer->getStore()->getName());
    }
}
