<?php


namespace Spryker\Shared\Sales;


use Spryker\Shared\Kernel\AbstractBundleConfig;

class SalesConfig extends AbstractBundleConfig
{
    public const ORDER_TYPE_DEFAULT = null;

    /**
     * @return null|string
     */
    public function getOrderTypeDefault()
    {
        return static::ORDER_TYPE_DEFAULT;
    }
}