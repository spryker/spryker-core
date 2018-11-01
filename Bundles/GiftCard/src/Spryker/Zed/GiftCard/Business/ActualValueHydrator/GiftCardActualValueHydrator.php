<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ActualValueHydrator;

use Generated\Shared\Transfer\GiftCardTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface;

class GiftCardActualValueHydrator implements GiftCardActualValueHydratorInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface
     */
    protected $giftCardValueProviderPlugin;

    /**
     * @param \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface $giftCardValueProviderPlugin
     */
    public function __construct(GiftCardValueProviderPluginInterface $giftCardValueProviderPlugin)
    {
        $this->giftCardValueProviderPlugin = $giftCardValueProviderPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function hydrate(GiftCardTransfer $giftCardTransfer)
    {
        $actualValue = $this->giftCardValueProviderPlugin->getValue($giftCardTransfer);
        $giftCardTransfer->setActualValue($actualValue);

        return $giftCardTransfer;
    }
}
