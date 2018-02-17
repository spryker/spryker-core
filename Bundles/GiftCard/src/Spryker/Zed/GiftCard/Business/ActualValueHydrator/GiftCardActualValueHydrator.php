<?php

namespace Spryker\Zed\GiftCard\Business\ActualValueHydrator;

use Generated\Shared\Transfer\GiftCardTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface;

class GiftCardActualValueHydrator implements  GiftCardActualValueHydratorInterface
{
    /**
     * @var GiftCardValueProviderPluginInterface
     */
    protected $giftCardValueProviderPlugin;

    public function __construct(GiftCardValueProviderPluginInterface $giftCardValueProviderPlugin)
    {
        $this->giftCardValueProviderPlugin = $giftCardValueProviderPlugin;
    }

    /**
     * @param GiftCardTransfer $giftCardTransfer
     *
     * @return GiftCardTransfer
     */
    public function hydrate(GiftCardTransfer $giftCardTransfer)
    {
        $actualValue = $this->giftCardValueProviderPlugin->getValue($giftCardTransfer);
        $giftCardTransfer->setActualValue($actualValue);

        return $giftCardTransfer;
    }
}