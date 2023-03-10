<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Communication\ProductOfferCommunicationFactory getFactory()
 */
class ProductOfferMerchantAclEntityRuleExpanderPlugin extends AbstractPlugin implements MerchantAclEntityRuleExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::SCOPE_INHERITED}
     *
     * @var string
     */
    protected const SCOPE_INHERITED = 'inherited';

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
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_UPDATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_UPDATE = 0b100;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_DELETE}
     *
     * @var int
     */
    protected const OPERATION_MASK_DELETE = 0b1000;

    /**
     * {@inheritDoc}
     * - Expands set of `AclEntityRule` transfer objects with product offer composite data.
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
            ->setEntity('Orm\Zed\ProductOffer\Persistence\SpyProductOffer')
            ->setScope(static::SCOPE_INHERITED)
            ->setPermissionMask(static::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore')
            ->setScope(static::SCOPE_GLOBAL)
            ->setPermissionMask(static::OPERATION_MASK_DELETE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore')
            ->setScope(static::SCOPE_INHERITED)
            ->setPermissionMask(static::OPERATION_MASK_READ | static::OPERATION_MASK_CREATE | static::OPERATION_MASK_UPDATE);

        return $aclEntityRuleTransfers;
    }
}
