<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Generator;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;

class AclMerchantPortalGenerator implements AclMerchantPortalGeneratorInterface
{
    /**
     * @var string
     */
    protected const KEY_MERCHANT_PORTAL = 'MerchantPortral';

    /**
     * @var \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig
     */
    protected AclMerchantPortalConfig $aclMerchantPortalConfig;

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
    public function generateAclMerchantRoleName(MerchantTransfer $merchantTransfer): string
    {
        return $this->generateMerchantPortalKeyToMerchantNameConjunction($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    public function generateAclMerchantGroupName(MerchantTransfer $merchantTransfer): string
    {
        return $this->generateMerchantPortalKeyToMerchantNameConjunction($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return string
     */
    public function generateAclMerchantUserRoleName(MerchantUserTransfer $merchantUserTransfer): string
    {
        return $this->generateMerchantNameToMerchantUserNameConjunction(
            $merchantUserTransfer->getMerchantOrFail(),
            $merchantUserTransfer->getUserOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return string
     */
    public function generateAclMerchantUserGroupName(MerchantUserTransfer $merchantUserTransfer): string
    {
        return $this->generateMerchantNameToMerchantUserNameConjunction(
            $merchantUserTransfer->getMerchantOrFail(),
            $merchantUserTransfer->getUserOrFail(),
        );
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
            $merchantTransfer->getMerchantReferenceOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    public function generateAclMerchantSegmentName(MerchantTransfer $merchantTransfer): string
    {
        return $this->generateMerchantPortalKeyToMerchantNameConjunction($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    public function generateAclMerchantUserSegmentName(MerchantTransfer $merchantTransfer, UserTransfer $userTransfer): string
    {
        return $this->generateMerchantNameToMerchantUserNameConjunction($merchantTransfer, $userTransfer);
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
            $userTransfer->getIdUserOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    protected function generateMerchantPortalKeyToMerchantNameConjunction(MerchantTransfer $merchantTransfer): string
    {
        return sprintf(
            '%s - %s',
            $merchantTransfer->getNameOrFail(),
            static::KEY_MERCHANT_PORTAL,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return string
     */
    protected function generateMerchantNameToMerchantUserNameConjunction(
        MerchantTransfer $merchantTransfer,
        UserTransfer $userTransfer
    ): string {
        if ($this->aclMerchantPortalConfig->isMerchantToMerchantUserConjunctionByUsernameEnabled()) {
            return sprintf(
                '%s - %s - %s',
                $merchantTransfer->getNameOrFail(),
                static::KEY_MERCHANT_PORTAL,
                $userTransfer->getUsernameOrFail(),
            );
        }

        return sprintf(
            '%s - %s - %s %s',
            $merchantTransfer->getNameOrFail(),
            static::KEY_MERCHANT_PORTAL,
            $userTransfer->getFirstNameOrFail(),
            $userTransfer->getLastNameOrFail(),
        );
    }
}
