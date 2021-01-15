<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAddressTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsCalculationsTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsCartTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsDiscountsTransfer;
use Generated\Shared\Transfer\RestQuoteRequestShipmentMethodTransfer;
use Generated\Shared\Transfer\RestQuoteRequestShipmentTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsItemTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsTotalsTransfer;
use Generated\Shared\Transfer\RestQuoteRequestVersionTransfer;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Service\QuoteRequestsRestApiToShipmentServiceInterface;

class QuoteRequestMapper implements QuoteRequestMapperInterface
{
    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Service\QuoteRequestsRestApiToShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Service\QuoteRequestsRestApiToShipmentServiceInterface $shipmentService
     */
    public function __construct(QuoteRequestsRestApiToShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer
     */
    public function mapQuoteRequestTransferToRestQuoteRequestsAttributesTransfer(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
    ): RestQuoteRequestsAttributesTransfer {
        $quoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersionOrFail();
        $restQuoteRequestVersionTransfer = (new RestQuoteRequestVersionTransfer())
            ->fromArray($quoteRequestVersionTransfer->toArray(), true)
            ->setMeta($quoteRequestVersionTransfer->getMetadata())
            ->setCart($this->buildRestCartTransfer($quoteRequestVersionTransfer->getQuoteOrFail()));

        $restQuoteRequestsAttributesTransfer
            ->fromArray($quoteRequestTransfer->toArray(), true)
            ->setShownVersion($restQuoteRequestVersionTransfer);

        return $restQuoteRequestsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function buildRestCartTransfer(QuoteTransfer $quoteTransfer): RestQuoteRequestsCartTransfer
    {
        $restCartTransfer = new RestQuoteRequestsCartTransfer();

        if (!$quoteTransfer->getItems()->count()) {
            return $restCartTransfer;
        }

        $restCartTransfer->setPriceMode($quoteTransfer->getPriceModeOrFail());
        $restCartTransfer->setCurrency($quoteTransfer->getCurrencyOrFail()->getCodeOrFail());
        $restCartTransfer->setStore($quoteTransfer->getStoreOrFail()->getNameOrFail());
        $restCartTransfer = $this->setBillingAddressToRestCartTransfer(
            $quoteTransfer,
            $restCartTransfer
        );

        $restCartTransfer = $this->setTotalsToRestCartTransfer(
            $quoteTransfer,
            $restCartTransfer
        );

        $restCartTransfer = $this->setDiscountsToRestCartTransfer(
            $quoteTransfer,
            $restCartTransfer
        );

        return $this->setShipmentsAndItemsToRestCartTransfer(
            $quoteTransfer,
            $restCartTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function setBillingAddressToRestCartTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteRequestsCartTransfer $restCartTransfer
    ): RestQuoteRequestsCartTransfer {
        $addressTransfer = $quoteTransfer->getBillingAddress();
        if ($addressTransfer !== null) {
            $restAddressTransfer = (new RestQuoteRequestsAddressTransfer())
                ->fromArray($addressTransfer->toArray(), true);
            $restCartTransfer->setBillingAddress($restAddressTransfer);
        }

        return $restCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function setTotalsToRestCartTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteRequestsCartTransfer $restCartTransfer
    ): RestQuoteRequestsCartTransfer {
        if ($quoteTransfer->getTotals() !== null) {
            $restTotalsTransfer = (new RestQuoteRequestsTotalsTransfer())
                ->fromArray($quoteTransfer->getTotalsOrFail()->toArray(), true)
                ->setTaxTotal($quoteTransfer->getTotalsOrFail()->getTaxTotalOrFail()->getAmountOrFail());

            $restCartTransfer->setTotals($restTotalsTransfer);
        }

        return $restCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function setDiscountsToRestCartTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteRequestsCartTransfer $restCartTransfer
    ): RestQuoteRequestsCartTransfer {
        foreach ($quoteTransfer->getVoucherDiscounts() as $voucherDiscount) {
            $restCartTransfer = $this->addDiscountToRestCartTransfer(
                $voucherDiscount,
                $restCartTransfer
            );
        }
        foreach ($quoteTransfer->getCartRuleDiscounts() as $discountTransfer) {
            $restCartTransfer = $this->addDiscountToRestCartTransfer(
                $discountTransfer,
                $restCartTransfer
            );
        }

        return $restCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function addDiscountToRestCartTransfer(
        DiscountTransfer $discountTransfer,
        RestQuoteRequestsCartTransfer $restCartTransfer
    ): RestQuoteRequestsCartTransfer {
        $restDiscountTransfer = new RestQuoteRequestsDiscountsTransfer();
        $restDiscountTransfer->fromArray($discountTransfer->toArray(), true);
        $restDiscountTransfer->setCode($discountTransfer->getVoucherCode());
        $restCartTransfer->addDiscount($restDiscountTransfer);

        return $restCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function setShipmentsAndItemsToRestCartTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteRequestsCartTransfer $restCartTransfer
    ): RestQuoteRequestsCartTransfer {
        if (!$this->isShipmentExistsForItems($quoteTransfer)) {
            return $this->addItemsToRestCartTransfer(
                $quoteTransfer,
                $restCartTransfer
            );
        }

        $shipmentGroupTransfers = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $shipmentTransfer = $shipmentGroupTransfer->getShipmentOrFail();
            $shipmentAddress = $shipmentTransfer->getShippingAddressOrFail();

            $restAddressTransfer = (new RestQuoteRequestsAddressTransfer())
                ->fromArray($shipmentAddress->toArray(), true);

            $itemGroupKeys = [];
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $restItemTransfer = $this->mapItemTransferToRestItemTransfer(
                    $itemTransfer,
                    new RestQuoteRequestsItemTransfer()
                );
                $restCartTransfer->addItem($restItemTransfer);

                $itemGroupKeys[] = $itemTransfer->getGroupKey();
            }

            $restShipmentTransfer = (new RestQuoteRequestShipmentTransfer())
                ->setShippingAddress($restAddressTransfer)
                ->setItems(array_filter($itemGroupKeys));

            if ($shipmentTransfer->getMethod() !== null) {
                $restShipmentTransfer->setMethod(
                    (new RestQuoteRequestShipmentMethodTransfer())
                        ->fromArray($shipmentTransfer->getMethodOrFail()->toArray(), true)
                        ->setPrice($shipmentTransfer->getMethod()->getStoreCurrencyPrice())
                );
            }

            $restCartTransfer->addShipment($restShipmentTransfer);
        }

        return $restCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function addItemsToRestCartTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteRequestsCartTransfer $restCartTransfer
    ): RestQuoteRequestsCartTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $restItemTransfer = $this->mapItemTransferToRestItemTransfer(
                $itemTransfer,
                new RestQuoteRequestsItemTransfer()
            );
            $restCartTransfer->addItem($restItemTransfer);
        }

        return $restCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentExistsForItems(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsItemTransfer $restItemTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsItemTransfer
     */
    protected function mapItemTransferToRestItemTransfer(
        ItemTransfer $itemTransfer,
        RestQuoteRequestsItemTransfer $restItemTransfer
    ): RestQuoteRequestsItemTransfer {
        $restItemTransfer->fromArray($itemTransfer->toArray(), true);
        $restCalculationsTransfer = new RestQuoteRequestsCalculationsTransfer();
        $restCalculationsTransfer->fromArray($itemTransfer->toArray(), true);
        $restItemTransfer->setCalculations($restCalculationsTransfer);

        return $restItemTransfer;
    }
}
