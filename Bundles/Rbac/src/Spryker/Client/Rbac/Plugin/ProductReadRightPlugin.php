<?php


namespace Spryker\Client\Rbac\Plugin;


class ProductReadRightPlugin implements RbacRightPluginInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return 'product.read';
    }
}