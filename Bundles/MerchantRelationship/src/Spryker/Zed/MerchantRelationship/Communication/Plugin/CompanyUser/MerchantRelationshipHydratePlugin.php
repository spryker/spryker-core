<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication\Plugin\CompanyUser;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserHydrationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface getFacade()
 */
class MerchantRelationshipHydratePlugin extends AbstractPlugin implements CompanyUserHydrationPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function hydrate(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        if ($companyUserTransfer->getCompanyBusinessUnit() !== null) {
            $merchantRelationships = $this->getRepository()->getMerchantRelationshipCollectionByIdAssignedBusinessUnit(
                $companyUserTransfer->getFkCompanyBusinessUnit()
            );

            $companyUserTransfer->getCompanyBusinessUnit()
                ->setMerchantRelationships(new ArrayObject($merchantRelationships));
        }

        return $companyUserTransfer;
    }
}
