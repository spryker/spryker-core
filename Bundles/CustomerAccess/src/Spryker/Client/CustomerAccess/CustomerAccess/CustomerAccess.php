<?php

namespace Spryker\Client\CustomerAccess\CustomerAccess;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\CustomerAccess\CustomerAccessConfig;
use Spryker\Client\CustomerAccess\Dependency\Client\CustomerAccessToCustomerAccessStorageClientInterface;

class CustomerAccess implements CustomerAccessInterface
{
    /**
     * @var \Spryker\Client\CustomerAccess\Dependency\Client\CustomerAccessToCustomerAccessStorageClientInterface
     */
    protected $customerAccessStorageReader;

    /**
     * @var \Spryker\Client\CustomerAccess\CustomerAccessConfig
     */
    protected $customerAccessConfig;

    /**
     * @param \Spryker\Client\CustomerAccess\Dependency\Client\CustomerAccessToCustomerAccessStorageClientInterface $customerAccessStorageReader
     * @param \Spryker\Client\CustomerAccess\CustomerAccessConfig $customerAccessConfig
     */
    public function __construct(CustomerAccessToCustomerAccessStorageClientInterface $customerAccessStorageReader, CustomerAccessConfig $customerAccessConfig)
    {
        $this->customerAccessStorageReader = $customerAccessStorageReader;
        $this->customerAccessConfig = $customerAccessConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getLoggedInCustomerPermissions(): PermissionCollectionTransfer
    {
        $authenticatedCustomerAccess = $this->customerAccessStorageReader->getAuthenticatedCustomerAccess();
        return $this->getPermissionsFromCustomerAccess($authenticatedCustomerAccess);
    }

    /**
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getLoggedOutCustomerPermissions(): PermissionCollectionTransfer
    {
        $unauthenticatedCustomerAccess = $this->customerAccessStorageReader->getUnauthenticatedCustomerAccess();
        return $this->getPermissionsFromCustomerAccess($unauthenticatedCustomerAccess);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function getPermissionsFromCustomerAccess(CustomerAccessTransfer $customerAccessTransfer)
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            $permission = new PermissionTransfer();
            $permission->setKey(
                $this->customerAccessConfig->getPluginNameToSeeContentType($contentTypeAccess->getContentType())
            );

            $permissionCollectionTransfer->addPermission($permission);
        }

        return $permissionCollectionTransfer;
    }
}
