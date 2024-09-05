<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesPaymentMerchantConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Represents the type for order items in payment transmission.
     *
     * @api
     *
     * @var string
     */
    public const PAYMENT_TRANSMISSION_ITEM_TYPE_ORDER_ITEM = 'order-item';

    /**
     * Specification:
     * - Represents the type for order expenses in payment transmission.
     *
     * @api
     *
     * @var string
     */
    public const PAYMENT_TRANSMISSION_ITEM_TYPE_ORDER_EXPENSE = 'order-expense';

    /**
     * @var string
     */
    public const ITEM_REFERENCE_SEPARATOR = ',';

    /**
     * @var array<string, list<string>>
     */
    protected const EXCLUDED_EXPENSE_TYPES_FOR_STORE = [];

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_CANCELED = 'canceled';

    /**
     * @var string
     */
    protected const OMS_STATE_PAYMENT_CLOSED = 'closed';

    /**
     * @var bool
     */
    protected const ORDER_EXPENSE_INCLUDED_IN_PAYMENT_PROCESS = false;

    /**
     * Specification:
     * - Provides a list of OMS states that are considered as refused payment states.
     *
     * @api
     *
     * @return array<string>
     */
    public function getItemRefusedStates(): array
    {
        return [
            static::OMS_STATE_PAYMENT_CANCELED,
            static::OMS_STATE_PAYMENT_CLOSED,
        ];
    }

    /**
     * Specification:
     * - Provides a list of expense types to be excluded from the transfer process for the given store.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return list<string>
     */
    public function getExcludedExpenseTypesForStore(string $storeName): array
    {
        return static::EXCLUDED_EXPENSE_TYPES_FOR_STORE[$storeName] ?? [];
    }

    /**
     * Specification:
     * - Determines whether order expenses should be included in the transfer process.
     *
     * @api
     *
     * @return bool
     */
    public function isOrderExpenseIncludedInPaymentProcess(): bool
    {
        return static::ORDER_EXPENSE_INCLUDED_IN_PAYMENT_PROCESS;
    }
}
