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
    public function can($permissionKey, $context = null)
    {
        $permissionCollectionTransfer = $this->findPermissions($permissionKey);

        if ($permissionCollectionTransfer->getPermissions()->count() === 0) {
            return false;
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