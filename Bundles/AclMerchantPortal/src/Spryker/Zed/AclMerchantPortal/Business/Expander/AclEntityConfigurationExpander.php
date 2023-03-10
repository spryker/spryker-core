<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Expander;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;

class AclEntityConfigurationExpander implements AclEntityConfigurationExpanderInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_CREATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_CREATE = 0b10;

    /**
     * @var array<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface>
     */
    protected array $aclEntityConfigurationExpanderPlugins;

    /**
     * @param list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface> $aclEntityConfigurationExpanderPlugins
     */
    public function __construct(array $aclEntityConfigurationExpanderPlugins)
    {
        $this->aclEntityConfigurationExpanderPlugins = $aclEntityConfigurationExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityConfiguration(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        }

        $aclEntityMetadataConfigTransfer = $this->expandAclEntityConfigurationWithEventBehaviorCompositeData($aclEntityMetadataConfigTransfer);

        return $this->executeAclEntityConfigurationExpanderPlugins($aclEntityMetadataConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function executeAclEntityConfigurationExpanderPlugins(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        foreach ($this->aclEntityConfigurationExpanderPlugins as $aclEntityConfigurationExpanderPlugin) {
            $aclEntityMetadataConfigTransfer = $aclEntityConfigurationExpanderPlugin->expand($aclEntityMetadataConfigTransfer);
        }

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    protected function expandAclEntityConfigurationWithEventBehaviorCompositeData(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->addAclEntityAllowListItem('Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange')
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
