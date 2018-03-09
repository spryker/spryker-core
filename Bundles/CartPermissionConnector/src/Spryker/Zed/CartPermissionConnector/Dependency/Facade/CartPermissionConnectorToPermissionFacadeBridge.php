<?php

namespace Spryker\Zed\CartPermissionConnector\Dependency\Facade;


use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

class CartPermissionConnectorToPermissionFacadeBridge implements CartPermissionConnectorToPermissionFacadeInterface
{
    /**
     * @var PermissionFacadeInterface
     */
    protected $permissionFacade;

    /**
     * @param PermissionFacadeInterface $permissionFacade
     */
    public function __construct($permissionFacade)
    {
        $this->permissionFacade = $permissionFacade;
    }

    /**
     * @param string $permissionKey
     * @param int|string $identifier
     * @param int|string|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $identifier, $context = null): bool
    {
        return $this->permissionFacade->can($permissionKey, $identifier, $context);
    }
}