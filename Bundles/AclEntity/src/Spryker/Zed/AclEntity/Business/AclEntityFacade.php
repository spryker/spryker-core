<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business;

use ArrayObject;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityRuleRequestTransfer;
use Generated\Shared\Transfer\AclEntityRuleResponseTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentResponseTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AclEntity\Business\AclEntityBusinessFactory getFactory()
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface getRepository()
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 */
class AclEntityFacade extends AbstractFacade implements AclEntityFacadeInterface
{
    use BundleConfigResolverAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentResponseTransfer
     */
    public function createAclEntitySegment(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): AclEntitySegmentResponseTransfer
    {
        return $this->getFactory()->createAclEntitySegmentWriter()->create($aclEntitySegmentRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleResponseTransfer
     */
    public function createAclEntityRule(AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer): AclEntityRuleResponseTransfer
    {
        return $this->getFactory()->createAclEntityRuleWriter()->create($aclEntityRuleRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param bool $runValidation
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function getAclEntityMetadataConfig(bool $runValidation = true): AclEntityMetadataConfigTransfer
    {
        return $this->getFactory()->createAclEntityMetadataConfigReader()->getAclEntityMetadataConfig($runValidation);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getFactory()->createAclEntityReader()->isActive();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\RolesTransfer
     */
    public function expandAclRoles(RolesTransfer $rolesTransfer): RolesTransfer
    {
        return $this->getFactory()->createAclRolesExpander()->expandAclRoles($rolesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @return void
     */
    public function saveAclEntityRules(ArrayObject $aclEntityRuleTransfers): void
    {
        $this->getFactory()->createAclEntityRuleWriter()->saveAclEntityRules($aclEntityRuleTransfers);
    }
}
