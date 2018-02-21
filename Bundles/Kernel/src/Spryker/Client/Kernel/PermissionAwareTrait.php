<?php


namespace Spryker\Client\Kernel;


trait PermissionAwareTrait
{
    /**
     * @param string $permissionKey
     * @param string|int|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null)
    {
        if (class_exists('\Spryker\Client\Permission\PermissionClientInterface')) {
            return Locator::getInstance()->permission()->client()->can($permissionKey, $context);
        }

        return true;
    }
}