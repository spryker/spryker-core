<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestCartsDiscountsTransfer;
use Generated\Shared\Transfer\RestCartsTotalsTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as SharedCartsRestApiConfig;

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
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestTransfer
     */
    public function mapRestQuoteRequestTransferFromRequest(
        QuoteResponseTransfer $quoteResponseTransfer,
        RestRequestInterface $restRequest
    ): RestQuoteRequestTransfer {
        if (count($quoteResponseTransfer->getErrorCodes()) > 0) {
            return (new RestQuoteRequestTransfer())->setErrorCodes(
                $this->mapQuoteResponseErrorsToErrorsCodes($quoteResponseTransfer->getErrorCodes())
            );
        }

        $retQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setQuote(new QuoteTransfer())
            ->setCustomerReference($quoteResponseTransfer->getCustomer()->getCustomerReference());

        if ($quoteResponseTransfer->getQuoteTransfer()) {
            $retQuoteRequestTransfer->setQuoteUuid($quoteResponseTransfer->getQuoteTransfer()->getUuid());
        }
        return $retQuoteRequestTransfer;
    }

    /**
     * @param string|null $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestTransfer
     */
    public function mapRestQuoteRequestTransferByUuid(
        ?string $uuidCart,
        RestRequestInterface $restRequest
    ): RestQuoteRequestTransfer {
        if (!$uuidCart) {
            return (new RestQuoteRequestTransfer())->addErrorCode(
                SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
            );
        }

        $restCartRequestTransfer = $this->createRestQuoteRequestTransfer($restRequest, (new QuoteTransfer())->setUuid($uuidCart));

        if (count($restCartRequestTransfer->getErrorCodes()) > 0) {
            return $restCartRequestTransfer;
        }

        $restCartRequestTransfer->getQuote()->setUuid($uuidCart);

        return $restCartRequestTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestTransfer
     */
    public function createRestQuoteRequestTransfer(
        RestRequestInterface $restRequest,
        ?QuoteTransfer $quoteTransfer
    ): RestQuoteRequestTransfer {
        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
        $uuidQuote = $restRequest->getResource()->getId();

        if (!$quoteTransfer && $uuidQuote) {
            $restQuoteRequestTransfer
                ->setQuote((new QuoteTransfer())->setUuid($uuidQuote))
                ->setQuoteUuid($uuidQuote);
        }

        $restQuoteRequestTransfer->setQuote($quoteTransfer->setUuid($uuidQuote));
        $restQuoteRequestTransfer->setQuoteUuid($uuidQuote);

        return $restQuoteRequestTransfer;
    }

    /**
     * @param bool $isSuccessful
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createRestQuoteResponseTransfer(
        bool $isSuccessful,
        ?QuoteTransfer $quoteTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setIsSuccessful($isSuccessful);

        if ($quoteTransfer) {
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapRestCartsAttributesTransferToQuoteTransfer(
        RestCartsAttributesTransfer $restCartsAttributesTransfer,
        RestRequestInterface $restRequest
    ): QuoteTransfer {
        $currencyTransfer = $this->createCurrencyTransfer($restCartsAttributesTransfer);
        $customerTransfer = $this->createCustomerTransfer($restRequest);
        $storeTransfer = $this->createStoreTransfer($restCartsAttributesTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->fromArray($restCartsAttributesTransfer->toArray(), true)
            ->setCurrency($currencyTransfer)
            ->setCustomer($customerTransfer)
            ->setPriceMode($restCartsAttributesTransfer->getPriceMode())
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer
     */
    public function mapRestRequestToRestQuoteCollectionRequestTransfer(
        RestRequestInterface $restRequest
    ): RestQuoteCollectionRequestTransfer {
        return (new RestQuoteCollectionRequestTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
    }

    /**
     * @param string|null $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(
        ?string $uuidCart,
        RestRequestInterface $restRequest
    ): QuoteTransfer {
        return (new QuoteTransfer())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setUuid($uuidCart);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerErrorTransfer[] $errors
     *
     * @return string[]
     */
    protected function mapQuoteResponseErrorsToErrorsCodes(array $errors): array
    {
        $errorCodes = [];

        foreach ($errors as $error) {
            $errorCodes[] = $error;
        }

        return $errorCodes;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function createCurrencyTransfer(RestCartsAttributesTransfer $restCartsAttributesTransfer): CurrencyTransfer
    {
        return (new CurrencyTransfer())
            ->setCode($restCartsAttributesTransfer->getCurrency());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(RestRequestInterface $restRequest): CustomerTransfer
    {
        return (new CustomerTransfer())->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransfer(RestCartsAttributesTransfer $restCartsAttributesTransfer): StoreTransfer
    {
        return (new StoreTransfer())
            ->setName($restCartsAttributesTransfer->getStore());
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
