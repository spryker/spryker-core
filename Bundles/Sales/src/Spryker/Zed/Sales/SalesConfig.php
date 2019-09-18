<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales;

use BadMethodCallException;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesConfig extends AbstractBundleConfig
{
    public const PARAM_ID_SALES_ORDER = 'id-sales-order';
    public const PARAM_CUSTOMER_REFERENCE = 'customer-reference';
    public const TEST_CUSTOMER_FIRST_NAME = 'test order';

    /**
     * Separator for the sequence number
     *
     * @return string
     */
    public function getUniqueIdentifierSeparator()
    {
        return '-';
    }

    /**
     * @return array
     */
    protected function getPaymentMethodStatemachineMapping()
    {
        return $this->get(SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING, []);
    }

    /**
     * Defines the prefix for the sequence number which is the public id of an order.
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getOrderReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(SalesConstants::NAME_ORDER_REFERENCE);

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = Store::getInstance()->getStoreName();
        $sequenceNumberPrefixParts[] = $this->get(SalesConstants::ENVIRONMENT_PREFIX);
        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts) . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * Defines logic to determine if order is placed for testing purposes. When order is persisted, is_test flag is set.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isTestOrder(QuoteTransfer $quoteTransfer)
    {
        if (!$this->hasItemLevelShipment($quoteTransfer)) {
            return $this->isTestOrderWithoutMultiShippingAddress($quoteTransfer);
        }

        return $this->isTestOrderWithMultiShippingAddress($quoteTransfer);
    }

    /**
     * This method determines state machine process from the given quote transfer and order item.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \BadMethodCallException
     *
     * @return string
     */
    public function determineProcessForOrderItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        throw new BadMethodCallException('You need to provide at least one state machine process for given method!');
    }

    /**
     * This method provides list of urls to render blocks inside order detail page.
     * URL defines path to external bundle controller. For example: /discount/sales/list would call discount bundle, sales controller, list action.
     * Action should return return array or redirect response.
     *
     * example:
     * [
     *    'discount' => '/discount/sales/index',
     * ]
     *
     * @return array
     */
    public function getSalesDetailExternalBlocksUrls()
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasItemLevelShipment(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isTestOrderWithoutMultiShippingAddress(QuoteTransfer $quoteTransfer): bool
    {
        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();
        if ($shippingAddressTransfer === null || $shippingAddressTransfer->getFirstName() !== static::TEST_CUSTOMER_FIRST_NAME) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isTestOrderWithMultiShippingAddress(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentTransfer = $itemTransfer->getShipment();

            if ($shipmentTransfer === null
                || $shipmentTransfer->getShippingAddress() === null
                || $shipmentTransfer->getShippingAddress()->getFirstName() !== static::TEST_CUSTOMER_FIRST_NAME
            ) {
                return false;
            }
        }

        return true;
    }
}
