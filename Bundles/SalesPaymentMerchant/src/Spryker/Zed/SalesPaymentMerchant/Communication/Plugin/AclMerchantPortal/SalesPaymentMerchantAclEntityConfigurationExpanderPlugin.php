<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\Business\SalesPaymentMerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentMerchant\Communication\SalesPaymentMerchantCommunicationFactory getFactory()()
 */
class SalesPaymentMerchantAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with sales payment merchant payout composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'))
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
