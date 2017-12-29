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
     * @param array $options
     *
     * @return bool
     */
    public function can($permissionKey, array $options)
    {
        $hasRight = $this->hasPermission($permissionKey);

        if ($hasRight === false) {
            return false;
        }

        $permissionRequestTransfer = new PermissionRequestTransfer();
        $permissionRequestTransfer->setPermissionKey($permissionKey);
        $permissionRequestTransfer->setOptions($options);


        return $this->getFactory()
            ->createZedStub()
            ->getIsAllowed($permissionRequestTransfer);
    }

    /**
     * Specification:
     * - KV lookup
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        return true;
    }

    public function getIsAllowed($permissionKey, array $options)
    {

    }
}