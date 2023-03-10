<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 */
class TaxAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with tax composite data.
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
                'Orm\Zed\Tax\Persistence\SpyTaxRate',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Tax\Persistence\SpyTaxRate')
                    ->setIsSubEntity(true)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Country\Persistence\SpyCountry')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Tax\Persistence\SpyTaxSet',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Tax\Persistence\SpyTaxSet')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
