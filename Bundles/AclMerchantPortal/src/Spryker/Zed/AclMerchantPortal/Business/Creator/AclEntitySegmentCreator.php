<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Creator;

use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface;

class AclEntitySegmentCreator implements AclEntitySegmentCreatorInterface
{
    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface
     */
    protected AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface
     */
    protected AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade
     * @param \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator
     */
    public function __construct(
        AclMerchantPortalToAclEntityFacadeInterface $aclEntityFacade,
        AclMerchantPortalGeneratorInterface $aclMerchantPortalGenerator
    ) {
        $this->aclEntityFacade = $aclEntityFacade;
        $this->aclMerchantPortalGenerator = $aclMerchantPortalGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    public function createMerchantAclEntitySegment(MerchantTransfer $merchantTransfer): AclEntitySegmentTransfer
    {
        $aclEntitySegmentRequestTransfer = (new AclEntitySegmentRequestTransfer())
            ->setName($this->aclMerchantPortalGenerator->generateAclMerchantSegmentName($merchantTransfer))
            ->setReference($this->aclMerchantPortalGenerator->generateAclMerchantReference($merchantTransfer))
            ->setEntity('Orm\Zed\Merchant\Persistence\SpyMerchant')
            ->addIdEntity($merchantTransfer->getIdMerchantOrFail());

        return $this->aclEntityFacade
            ->createAclEntitySegment($aclEntitySegmentRequestTransfer)
            ->getAclEntitySegmentOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentTransfer
     */
    public function createMerchantUserAclEntitySegment(MerchantUserTransfer $merchantUserTransfer): AclEntitySegmentTransfer
    {
        $aclMerchantUserSegmentName = $this->aclMerchantPortalGenerator->generateAclMerchantUserSegmentName(
            $merchantUserTransfer->getMerchantOrFail(),
            $merchantUserTransfer->getUserOrFail(),
        );
        $aclMerchantUserReference = $this->aclMerchantPortalGenerator->generateAclMerchantUserReference(
            $merchantUserTransfer->getUserOrFail(),
        );

        $aclEntitySegmentRequestTransfer = (new AclEntitySegmentRequestTransfer())
            ->setName($aclMerchantUserSegmentName)
            ->setReference($aclMerchantUserReference)
            ->setEntity('Orm\Zed\MerchantUser\Persistence\SpyMerchantUser')
            ->addIdEntity($merchantUserTransfer->getIdMerchantUserOrFail());

        return $this->aclEntityFacade
            ->createAclEntitySegment($aclEntitySegmentRequestTransfer)
            ->getAclEntitySegmentOrFail();
    }
}
