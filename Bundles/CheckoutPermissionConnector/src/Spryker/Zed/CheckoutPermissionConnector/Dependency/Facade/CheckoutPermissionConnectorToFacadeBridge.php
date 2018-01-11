<?php

namespace Spryker\Zed\CheckoutPermissionConnector\Dependency;


use Spryker\Zed\Permission\Business\PermissionFacadeInterface;

class CheckoutPermissionConnectorToFacadeBridge
{
    /** @var  PermissionFacadeInterface */
    protected $permissionFacade;

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
    public function can($permissionKey, $identifier, $context = null)
    {
        return $this->permissionFacade->can($permissionKey, $identifier, $context);
    }
}