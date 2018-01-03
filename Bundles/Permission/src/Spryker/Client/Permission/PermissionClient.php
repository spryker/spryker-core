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
        $hasRight = $this->hasPermission($permissionKey);

        if ($hasRight === false) {
            return true;
        }

        $permissionRequestTransfer = new PermissionRequestTransfer();
        $permissionRequestTransfer->setPermissionKey($permissionKey);
        $permissionRequestTransfer->setContext($context);

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
        //go to the user session and check the permission
        return true;
    }

    public function getIsAllowed($permissionKey, array $options)
    {

    }
}