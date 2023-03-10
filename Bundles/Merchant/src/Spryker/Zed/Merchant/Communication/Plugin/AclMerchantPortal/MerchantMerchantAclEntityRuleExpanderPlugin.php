<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 * @method \Spryker\Zed\Merchant\Communication\MerchantCommunicationFactory getFactory()
 */
class MerchantMerchantAclEntityRuleExpanderPlugin extends AbstractPlugin implements MerchantAclEntityRuleExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::SCOPE_SEGMENT}
     *
     * @var string
     */
    protected const SCOPE_SEGMENT = 'segment';

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_UPDATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_UPDATE = 0b100;

    /**
     * {@inheritDoc}
     * - Expands set of `AclEntityRule` transfer objects with merchant composite data.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return list<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function expand(array $aclEntityRuleTransfers): array
    {
        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Merchant\Persistence\SpyMerchant')
            ->setScope(static::SCOPE_SEGMENT)
            ->setPermissionMask(static::OPERATION_MASK_READ | static::OPERATION_MASK_UPDATE);

        return $aclEntityRuleTransfers;
    }
}
