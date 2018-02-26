<?php


namespace Spryker\Client\Kernel;


trait CanAwareTrait
{
    /**
     * @param string $permissionKey
     * @param string|int|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null)
    {
        if (interface_exists('\Spryker\Client\Permission\PermissionClientInterface')) {
            return Locator::getInstance()->permission()->client()->can($permissionKey, $context);
        }

        return true;
    }
}