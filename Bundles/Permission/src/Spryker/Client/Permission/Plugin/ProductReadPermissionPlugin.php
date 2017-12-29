<?php

namespace Spryker\Client\Permission\Plugin;

class ProductReadPermissionPlugin implements PermissionPluginInterface
{
    public function getKey()
    {
        return 'product.read';
    }
}