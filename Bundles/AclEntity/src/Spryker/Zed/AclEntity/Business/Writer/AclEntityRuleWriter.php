<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\AclEntityRuleRequestTransfer;
use Generated\Shared\Transfer\AclEntityRuleResponseTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface;

class AclEntityRuleWriter implements AclEntityRuleWriterInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface
     */
    protected $aclEntityEntityManager;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface $aclEntityEntityManager
     */
    public function __construct(AclEntityEntityManagerInterface $aclEntityEntityManager)
    {
        $this->aclEntityEntityManager = $aclEntityEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleResponseTransfer
     */
    public function create(AclEntityRuleRequestTransfer $aclEntityRuleRequestTransfer): AclEntityRuleResponseTransfer
    {
        $aclEntityRuleTransfer = $this->aclEntityEntityManager->createAclEntityRule($aclEntityRuleRequestTransfer);

        return (new AclEntityRuleResponseTransfer())
            ->setAclEntityRule($aclEntityRuleTransfer)
            ->setIsSuccess(true);
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\AclEntityRuleTransfer> $aclEntityRuleTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\AclEntityRuleTransfer[] $aclEntityRuleTransfers
     *
     * @return void
     */
    public function saveAclEntityRules(ArrayObject $aclEntityRuleTransfers): void
    {
        foreach ($aclEntityRuleTransfers as $aclEntityRuleTransfer) {
            $aclEntityRuleRequestTransfer = $this->getAclEntityRuleRequestTransfer($aclEntityRuleTransfer);
            $this->aclEntityEntityManager->createAclEntityRule($aclEntityRuleRequestTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleTransfer $aclEntityRuleTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleRequestTransfer
     */
    protected function getAclEntityRuleRequestTransfer(AclEntityRuleTransfer $aclEntityRuleTransfer): AclEntityRuleRequestTransfer
    {
        return (new AclEntityRuleRequestTransfer())->fromArray($aclEntityRuleTransfer->toArray());
    }
}
