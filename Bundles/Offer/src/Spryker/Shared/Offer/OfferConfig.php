<?php

namespace Spryker\Shared\Offer;


use Spryker\Shared\Kernel\AbstractBundleConfig;

class OfferConfig extends AbstractBundleConfig
{
    public const ORDER_TYPE_OFFER = 'offer';

    /**
     * @uses \Spryker\Shared\Sales\SalesConfig::ORDER_TYPE_DEFAULT
     */
    public const ORDER_TYPE_DEFAULT = null;

    /**
     * @return string
     */
    public function getOrderTypeOffer(): string
    {
        return static::ORDER_TYPE_OFFER;
    }

    /**
     * @return null|string
     */
    public function getOrderTypeDefault(): ?string
    {
        return static::ORDER_TYPE_DEFAULT;
    }
}