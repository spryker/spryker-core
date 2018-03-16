<?php

namespace Spryker\Client\CustomerAccess\Plugin;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class SeePricePermissionPlugin extends AbstractPlugin implements PermissionPluginInterface
{
    const KEY = 'SeePrice';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
