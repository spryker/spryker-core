<?php


namespace Spryker\Shared\GiftCard;


use Spryker\Shared\Kernel\AbstractBundleConfig;

class GiftCardConfig extends AbstractBundleConfig
{
    const PROVIDER_NAME = 'GiftCard';
    const ERROR_GIFT_CARD_ALREADY_USED = 407;
    const ERROR_GIFT_CARD_AMOUNT_TOO_HIGH = 408;
}