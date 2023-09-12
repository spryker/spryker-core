<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePoint\Communication\ProductOfferServicePointCommunicationFactory getFactory()
 */
class ProductOfferServicePointAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with product offer service point composite data.
     *
     * @api
     *
     * @inheritDoc
     */
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        $aclEntityMetadataConfigTransfer
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferService',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferService')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\ProductOffer\Persistence\SpyProductOffer'))
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
