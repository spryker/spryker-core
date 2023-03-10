<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface getFacade()
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Communication\StateMachineCommunicationFactory getFactory()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 */
class StateMachineAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
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
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_DELETE}
     *
     * @var int
     */
    protected const OPERATION_MASK_DELETE = 0b1000;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with state machine composite data.
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
                'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\StateMachine\Persistence\SpyStateMachineLock',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineLock')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ | static::OPERATION_MASK_DELETE),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
