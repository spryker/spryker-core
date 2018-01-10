<?php


namespace Spryker\Client\Permission;

use Generated\Shared\Transfer\PermissionRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Permission\PermissionFactory getFactory()
 */
class PermissionClient extends AbstractClient implements PermissionClientInterface
{
    /**
     * @param string $permissionKey
     * @param array|mixed|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null)
    {
        return $this->getFactory()
            ->createPermissionExecutor()
            ->can($permissionKey, $context);
    }
}