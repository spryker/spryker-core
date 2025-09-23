<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchant\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DataImportMerchant\Business\DataImportMerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig getConfig()
 */
class DataImportMerchantFileAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with data import merchant file composite data.
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
                'Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile')
                    ->setParent(
                        (new AclEntityParentMetadataTransfer())
                            ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')
                            ->setConnection((new AclEntityParentConnectionMetadataTransfer())->setReference('merchant_reference')->setReferencedColumn('merchant_reference')),
                    )
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
