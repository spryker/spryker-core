<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProduct\PriceProductConfig getConfig()
 * @method \Spryker\Zed\PriceProduct\Communication\PriceProductCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface getQueryContainer()
 */
class PriceProductMerchantAclEntityRuleExpanderPlugin extends AbstractPlugin implements MerchantAclEntityRuleExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::SCOPE_GLOBAL}
     *
     * @var string
     */
    protected const SCOPE_GLOBAL = 'global';

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_CRUD}
     *
     * @var int
     */
    protected const OPERATION_MASK_CRUD = 0b1111;

    /**
     * {@inheritDoc}
     * - Expands set of `AclEntityRule` transfer objects with price product composite data.
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
            ->setEntity('Orm\Zed\PriceProduct\Persistence\SpyPriceProduct')
            ->setScope(static::SCOPE_GLOBAL)
            ->setPermissionMask(static::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore')
            ->setScope(static::SCOPE_GLOBAL)
            ->setPermissionMask(static::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\PriceProduct\Persistence\SpyPriceType')
            ->setScope(static::SCOPE_GLOBAL)
            ->setPermissionMask(static::OPERATION_MASK_CRUD);

        return $aclEntityRuleTransfers;
    }
}
