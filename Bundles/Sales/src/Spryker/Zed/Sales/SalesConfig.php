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
    /**
     * @var string
     */
    public const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @var string
     */
    public const PARAM_CUSTOMER_REFERENCE = 'customer-reference';

    /**
     * @var string
     */
    public const TEST_CUSTOMER_FIRST_NAME = 'test order';

    /**
     * Specification:
     * - Regular expression to validate First Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_FIRST_NAME = '/^[^:\/<>]+$/';

    /**
     * Specification:
     * - Regular expression to validate Last Name field.
     *
     * @api
     *
     * @var string
     */
    public const PATTERN_LAST_NAME = '/^[^:\/<>]+$/';

    /**
     * @var string
     */
    public const UNIQUE_RANDOM_ID_ORDER_REFERENCE_ALPHABET = '0123456789';

    /**
     * @var int
     */
    public const UNIQUE_RANDOM_ID_ORDER_REFERENCE_SIZE = 15;

    /**
     * @var int
     */
    public const UNIQUE_RANDOM_ID_ORDER_REFERENCE_SPLIT_LENGTH = 5;

    /**
     * Separator for the sequence number
     *
     * @api
     *
     * @return string
     */
    public function getUniqueIdentifierSeparator()
    {
        return '-';
    }

    /**
     * @api
     *
     * @example The format of returned array is:
     * [
     *    'PAYMENT_METHOD_1' => 'StateMachineProcess_1',
     *    'PAYMENT_METHOD_2' => 'StateMachineProcess_2',
     * ]
     *
     * @return array<string, string>
     */
    public function getPaymentMethodStatemachineMapping()
    {
        return $this->get(SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING, []);
    }

    /**
     * Defines the prefix for the sequence number which is the public id of an order.
     *
     * @api
     *
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getOrderReferenceDefaults(?string $storeName = null)
    {
        $storeName = $this->resolveStoreName($storeName);

        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(SalesConstants::NAME_ORDER_REFERENCE);

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = $storeName;
        $sequenceNumberPrefixParts[] = $this->get(SalesConstants::ENVIRONMENT_PREFIX, '');
        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts) . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * Defines logic to determine if order is placed for testing purposes. When order is persisted, is_test flag is set.
     *
     * @api
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
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Sales\Business\StateMachineResolver\OrderStateMachineResolver::resolve()} instead.
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
     * @api
     *
     * @return array<string>
     */
    public function getSalesDetailExternalBlocksUrls()
    {
        return [];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isHydrateOrderHistoryToItems(): bool
    {
        return true;
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

            if (
                $shipmentTransfer === null
                || $shipmentTransfer->getShippingAddress() === null
                || $shipmentTransfer->getShippingAddress()->getFirstName() !== static::TEST_CUSTOMER_FIRST_NAME
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string|null $storeName
     *
     * @return string
     */
    protected function resolveStoreName(?string $storeName): string
    {
        return $storeName ?? Store::getInstance()->getStoreName();
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    public function isOldDeterminationForOrderItemProcessEnabled(): bool
    {
        return true;
    }

    /**
     * Specification:
     * - Returns true if order items should be updated during order update {@link SalesFacade::updateOrder()}.
     *
     * @api
     *
     * @return bool
     */
    public function shouldPersistModifiedOrderItemProperties(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Defines if the order reference should be generated using the UniqueRandomIdOrderReferenceGenerator, instead of SequenceNumberOrderReferenceGenerator. UniqueRandomIdOrderReferenceGenerator does not use a database, so it's faster in concurrent requests.
     *
     * @api
     *
     * @return bool
     */
    public function useUniqueRandomIdOrderReferenceGenerator(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Returns the alphabet for the UniqueRandomId order reference. When more symbols are used, there are fewer chances for collision.
     *
     * Example: '0123456789abcdefg'
     *
     * @api
     *
     * @return string
     */
    public function getUniqueRandomIdOrderReferenceAlphabet(): string
    {
        return static::UNIQUE_RANDOM_ID_ORDER_REFERENCE_ALPHABET;
    }

    /**
     * Specification:
     * - Returns the length of the UniqueRandomId order reference. Longer size - fewer chances for collision.
     *
     * @api
     *
     * @return int
     */
    public function getUniqueRandomIdOrderReferenceSize(): int
    {
        return static::UNIQUE_RANDOM_ID_ORDER_REFERENCE_SIZE;
    }

    /**
     * Specification:
     * - Returns the split length for the UniqueRandomId order reference.
     *
     * @api
     *
     * @return int
     */
    public function getUniqueRandomIdOrderReferenceSplitLength(): int
    {
        return static::UNIQUE_RANDOM_ID_ORDER_REFERENCE_SPLIT_LENGTH;
    }

    /**
     * Specification:
     * - Defines the column used in order batch save as a unique identifier. This column must contain unique values and can be used for hashing or indexing purposes.
     *
     * @api
     *
     * @return string
     */
    public function getItemHashColumn(): string
    {
        return '';
    }
}
