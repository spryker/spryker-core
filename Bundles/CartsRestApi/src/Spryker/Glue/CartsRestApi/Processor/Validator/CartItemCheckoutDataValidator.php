<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class CartItemCheckoutDataValidator implements CartItemCheckoutDataValidatorInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(CartsRestApiClientInterface $cartsRestApiClient)
    {
        $this->cartsRestApiClient = $cartsRestApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateCartItemCheckoutData(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();

        if (!$restCheckoutRequestAttributesTransfer->getShipments()->count()) {
            return $restErrorCollectionTransfer;
        }

        $quoteTransfer = $this->findQuote($restCheckoutRequestAttributesTransfer);

        if (!$quoteTransfer) {
            return $restErrorCollectionTransfer;
        }

        $itemGroupKeys = $this->extractItemGroupKeys($restCheckoutRequestAttributesTransfer);

        $restErrorCollectionTransfer = $this->validateItemLevel($restErrorCollectionTransfer, $quoteTransfer, $itemGroupKeys);
        $restErrorCollectionTransfer = $this->validateBundleItemLevel($restErrorCollectionTransfer, $quoteTransfer, $itemGroupKeys);

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $itemGroupKeys
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateItemLevel(
        RestErrorCollectionTransfer $restErrorCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        array $itemGroupKeys
    ): RestErrorCollectionTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() || $this->isGiftCard($itemTransfer)) {
                continue;
            }

            if (!in_array($itemTransfer->getGroupKey(), $itemGroupKeys, true)) {
                $this->buildErrorMessage(
                    $restErrorCollectionTransfer,
                    sprintf(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ITEM_WAS_NOT_SPECIFIED_IN_CHECKOUT_DATA, $itemTransfer->getGroupKey()),
                    CartsRestApiConfig::RESPONSE_CODE_CART_ITEM_WAS_NOT_SPECIFIED_IN_CHECKOUT_DATA
                );
            }
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isGiftCard(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getGiftCardMetadata() && $itemTransfer->getGiftCardMetadata()->getIsGiftCard();
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $itemGroupKeys
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validateBundleItemLevel(
        RestErrorCollectionTransfer $restErrorCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        array $itemGroupKeys
    ): RestErrorCollectionTransfer {
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getGroupKey(), $itemGroupKeys, true)) {
                $this->buildErrorMessage(
                    $restErrorCollectionTransfer,
                    sprintf(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ITEM_WAS_NOT_SPECIFIED_IN_CHECKOUT_DATA, $itemTransfer->getGroupKey()),
                    CartsRestApiConfig::RESPONSE_CODE_CART_ITEM_WAS_NOT_SPECIFIED_IN_CHECKOUT_DATA
                );
            }
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return string[]
     */
    protected function extractItemGroupKeys(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        $itemGroupKeys = [];

        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            $itemGroupKeys[] = $restShipmentsTransfer->getItems();
        }

        return array_merge([], ...$itemGroupKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuote(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        $customerReference = $restCheckoutRequestAttributesTransfer
            ->requireIdCart()
            ->requireRestUser()
            ->getRestUser()
                ->requireNaturalIdentifier()
                ->getNaturalIdentifier();

        $quoteTransfer = (new QuoteTransfer())
            ->setUuid($restCheckoutRequestAttributesTransfer->getIdCart())
            ->setCustomerReference($customerReference);

        $quoteResponseTransfer = $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);

        return $quoteResponseTransfer->getQuoteTransfer() ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     * @param string $detail
     * @param string $code
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function buildErrorMessage(
        RestErrorCollectionTransfer $restErrorCollectionTransfer,
        string $detail,
        string $code,
        ?int $status = Response::HTTP_NOT_FOUND
    ): RestErrorCollectionTransfer {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setCode($code)
            ->setStatus($status);

        return $restErrorCollectionTransfer->addRestError($restErrorMessageTransfer);
    }
}
