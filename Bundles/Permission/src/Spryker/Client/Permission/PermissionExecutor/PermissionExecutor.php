<?php

namespace Spryker\Client\Permission\PermissionExecutor;


use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientInterface;
use Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface;
use Spryker\Client\Permission\Plugin\ExecutablePermissionPluginInterface;

class PermissionExecutor implements PermissionExecutorInterface
{
    /**
     * @var PermissionFinderInterface
     */
    protected $permissionFinder;

    /**
     * @var PermissionToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param PermissionToCustomerClientInterface $customerClient
     * @param PermissionFinderInterface $permissionConfigurator
     */
    public function __construct(
        PermissionToCustomerClientInterface $customerClient,
        PermissionFinderInterface $permissionConfigurator
    ) {
        $this->permissionFinder = $permissionConfigurator;
        $this->customerClient = $customerClient;
    }

    /**
     * @param string $permissionKey
     * @param string|int|array|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null): bool
    {
        $permissionCollectionTransfer = $this->findPermissions($permissionKey);

        if ($permissionCollectionTransfer->getPermissions()->count() === 0) {
            return false;
        }

        return $this->executePermissionCollection($permissionCollectionTransfer, $context);
    }

    /**
     * If one of the permission configurations wins, then a subject has the permission
     * Example: even if an admin user assigned to a junior sales manager role (with up to 1000 euro order),
     *  the user could perform actions as an admin.
     *
     * @param PermissionCollectionTransfer $permissionCollectionTransfer
     * @param null $context
     *
     * @return bool
     */
    protected function executePermissionCollection(PermissionCollectionTransfer $permissionCollectionTransfer, $context = null): bool
    {
        $hasPermission = false;

        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            $hasPermission |= $this->executePermission($permissionTransfer, $context);
        }

        return (bool)$hasPermission;
    }

    /**
     * @param PermissionTransfer $permissionTransfer
     * @param null $context
     *
     * @return bool
     */
    protected function executePermission(PermissionTransfer $permissionTransfer, $context = null): bool
    {
        $permissionPlugin = $this->permissionFinder->getPermissionPlugin($permissionTransfer);

        if (!$permissionPlugin) {
            return true;
        }

        if (!($permissionPlugin instanceof ExecutablePermissionPluginInterface)) {
            return true;
        }

        return $permissionPlugin->can($permissionTransfer->getConfiguration(), $context);
    }

    /**
     * @param string $permissionKey
     *
     * @return PermissionCollectionTransfer
     */
    protected function findPermissions($permissionKey): PermissionCollectionTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        $companyUser = $this->customerClient->getCompanyUser();
        /** @var CompanyRoleTransfer $companyRole */
        foreach ($companyUser->getCompanyRoleCollection() as $companyRole) {

            /** @var PermissionTransfer $permission */
            foreach ($companyRole->getPermissionCollection() as $permission) {
                if ($permission->getKey() === $permissionKey) {
                    $permissionCollectionTransfer->addPermission($permission);
                }
            }
        }

        return $permissionCollectionTransfer;
    }

}