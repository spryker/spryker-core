<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication\Plugin\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostDeletePluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationship\Communication\MerchantRelationshipCommunicationFactory getFactory()
 */
class CompanyBusinessUnitNotificationMerchantRelationshipPostDeletePlugin extends AbstractPlugin implements MerchantRelationshipPostDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantRelationships.fkMerchant` to be set.
     * - Requires `MerchantRelationships.ownerCompanyBusinessUnit.idCompanyBusinessUnit` to be set.
     * - Requires `MerchantRelationships.assigneeCompanyBusinessUnits' to be set.
     * - Expects `MerchantRelationships.assigneeCompanyBusinessUnits.companyBusinessUnits' to be set.
     * - Requires `MerchantRelationships.assigneeCompanyBusinessUnits.companyBusinessUnits.idCompanyBusinessUnit' to be set.
     * - Does nothing when merchant's owner company business unit don't have email address.
     * - Adds assignee company business unit email addresses as recipients BCC.
     * - Sends a notification email about deleted merchant relationships to company business units.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function execute(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $this->getFacade()->sendMerchantRelationshipDeleteMailNotification($merchantRelationshipTransfer);
    }
}
