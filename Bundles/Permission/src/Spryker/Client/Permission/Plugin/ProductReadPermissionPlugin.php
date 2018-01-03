<?php

namespace Spryker\Client\Permission\Plugin;

/**
 * @example
 */
class ProductReadPermissionPlugin implements PermissionPluginInterface
{
    public function getKey()
    {
        return 'product.read';
    }
}