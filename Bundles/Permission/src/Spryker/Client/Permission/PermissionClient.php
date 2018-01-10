<?php


namespace Spryker\Client\Permission;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Permission\PermissionFactory getFactory()
 */
class PermissionClient extends AbstractClient implements PermissionClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $permissionKey
     * @param string|int|array|null $context
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