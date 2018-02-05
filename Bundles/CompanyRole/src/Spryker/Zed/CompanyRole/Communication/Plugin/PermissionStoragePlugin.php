<?php


namespace Spryker\Zed\CompanyRole\Communication\Plugin;


use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Permission\Communication\Plugin\PermissionStoragePluginInterface;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 */
class PermissionStoragePlugin extends AbstractPlugin implements PermissionStoragePluginInterface
{
    /**
     * @param int|string $identifier
     *
     * @return PermissionCollectionTransfer
     */
    public function getPermissionCollection($identifier): PermissionCollectionTransfer
    {
        return $this->getFacade()->findPermissionsByIdCompanyUser((int)$identifier);
    }

}