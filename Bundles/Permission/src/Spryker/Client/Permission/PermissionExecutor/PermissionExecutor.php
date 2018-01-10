<?php

namespace Spryker\Client\Permission\PermissionExecutor;


use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Permission\Dependency\Client\PermissionToCustomerClientInterface;
use Spryker\Client\Permission\PermissionConfigurator\PermissionConfiguratorInterface;

class PermissionExecutor implements PermissionExecutorInterface
{
    /**
     * @var PermissionConfiguratorInterface
     */
    protected $permissionConfigurator;

    protected $customerClient;

    public function __construct(
        PermissionToCustomerClientInterface $customerClient,
        PermissionConfiguratorInterface $permissionConfigurator
    ) {
        $this->permissionConfigurator = $permissionConfigurator;
        $this->customerClient = $customerClient;
    }

    public function can($permissionKey, $context = null)
    {
        $permissionCollectionTransfer = $this->findPermissions($permissionKey);

        if ($permissionCollectionTransfer->getPermissions()->count() === 0) {
            return false;
        }

        if (!$this->permissionConfigurator->isExecutable($permissionKey)) {
            return true;
        }

        return $this->executePermissionCollection($permissionCollectionTransfer, $context);
    }

    /**
     * @param PermissionCollectionTransfer $permissionCollectionTransfer
     * @param null $context
     *
     * @return bool
     */
    protected function executePermissionCollection(PermissionCollectionTransfer $permissionCollectionTransfer, $context = null)
    {
        foreach ($permissionCollectionTransfer->getPermissions() as $permissionTransfer) {
            if (!$this->executePermission($permissionTransfer, $context)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param PermissionTransfer $permissionTransfer
     * @param null $context
     *
     * @return bool
     */
    protected function executePermission(PermissionTransfer $permissionTransfer, $context = null)
    {
        $permissionPlugin = $this->permissionConfigurator->configurePermission($permissionTransfer);

        return $permissionPlugin->can($context);
    }

    /**
     * @param string $permissionKey
     *
     * @return PermissionCollectionTransfer
     */
    protected function findPermissions($permissionKey)
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