<?php

namespace Spryker\Client\CompanyUser\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Permission\Plugin\PermissionPluginInterface;

class AddCompanyUserPermissionPlugin extends AbstractPlugin implements PermissionPluginInterface
{
    public const KEY = 'allow.company.user.add';

    /**
     * @return string
     */
    public function getKey()
    {
        return static::KEY;
    }
}