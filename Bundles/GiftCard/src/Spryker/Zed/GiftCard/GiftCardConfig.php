<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class GiftCardConfig extends AbstractBundleConfig
{
    public const PROVIDER_NAME = 'GiftCard';

    /**
     * @api
     *
     * @return string
     */
    public function getPaymentProviderName()
    {
        return static::PROVIDER_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPaymentMethodName()
    {
        return static::PROVIDER_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCodePrefix()
    {
        return 'GC';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCodeSuffix()
    {
        return date('y');
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCodeRandomPartLength()
    {
        return 8;
    }

    /**
     * @api
     *
     * @deprecated Use {@link getGiftCardPaymentMethodBlacklist()} instead.
     *
     * @return array
     */
    public function getGiftCardMethodBlacklist()
    {
        return [];
    }

    /**
     * Provides a list of payment method names that are disabled to use when the quote contains gift card item(s) to purchase.
     *
     * @api
     *
     * @return string[]
     */
    public function getGiftCardPaymentMethodBlacklist(): array
    {
        return $this->getGiftCardMethodBlacklist();
    }

    /**
     * Provides a list of shipment method names that should be available in case there are only gift card items in the quote.
     *
     * @api
     *
     * @return string[]
     */
    public function getGiftCardOnlyShipmentMethods(): array
    {
        return [];
    }
}
