<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Generator;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;

class AclMerchantPortalGenerator implements AclMerchantPortalGeneratorInterface
{
    protected const KEY_MERCHANT_PORTAL = 'MerchantPortral';

    /**
     * @var \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig
     */
    protected $aclMerchantPortalConfig;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig $aclMerchantPortalConfig
     */
    public function __construct(AclMerchantPortalConfig $aclMerchantPortalConfig)
    {
        $this->aclMerchantPortalConfig = $aclMerchantPortalConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    public function generateAclMerchantReference(MerchantTransfer $merchantTransfer): string
    {
        return sprintf(
            '%s%s',
            $this->aclMerchantPortalConfig->getMerchantAclReferencePrefix(),
            $merchantTransfer->getMerchantReferenceOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    public function generateAclMerchantUserSegmentName(MerchantTransfer $merchantTransfer, UserTransfer $userTransfer): string
    {
        return sprintf(
            '%s-%s-%s %s',
            $merchantTransfer->getNameOrFail(),
            static::KEY_MERCHANT_PORTAL,
            $userTransfer->getFirstNameOrFail(),
            $userTransfer->getLastNameOrFail()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    public function generateAclMerchantUserReference(UserTransfer $userTransfer): string
    {
        return sprintf(
            '%s%d',
            $this->aclMerchantPortalConfig->getMerchantUserAclReferencePrefix(),
            $userTransfer->getIdUserOrFail()
        );
    }
}
