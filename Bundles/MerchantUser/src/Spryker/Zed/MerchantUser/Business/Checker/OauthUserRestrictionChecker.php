<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business\Checker;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;
use Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface;

class OauthUserRestrictionChecker implements OauthUserRestrictionCheckerInterface
{
    protected const MESSAGE_MERCHANT_USER_CANNOT_AUTHORIZE = 'Merchant users cannot be authorized!';

    /**
     * @var \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface
     */
    protected $merchantUserRepository;

    /**
     * @param \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface $merchantUserRepository
     */
    public function __construct(MerchantUserRepositoryInterface $merchantUserRepository)
    {
        $this->merchantUserRepository = $merchantUserRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer
     */
    public function isOauthUserRestricted(
        OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
    ): OauthUserRestrictionResponseTransfer {
        $oauthUserRestrictionRequestTransfer
            ->requireUser()
            ->getUser()
            ->requireUsername();

        $oauthUserRestrictionResponseTransfer = (new OauthUserRestrictionResponseTransfer())
            ->setIsRestricted(false);

        $merchantUserCriteriaTransfer = $this->createMerchantUserCriteriaTransfer($oauthUserRestrictionRequestTransfer);

        $merchantUserTransfer = $this->merchantUserRepository->findOne($merchantUserCriteriaTransfer);
        if ($merchantUserTransfer !== null) {
            return $oauthUserRestrictionResponseTransfer->setIsRestricted(true)
                ->addMessage(
                    $this->createErrorMessage(static::MESSAGE_MERCHANT_USER_CANNOT_AUTHORIZE)
                );
        }

        return $oauthUserRestrictionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserCriteriaTransfer
     */
    protected function createMerchantUserCriteriaTransfer(
        OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
    ): MerchantUserCriteriaTransfer {
        return (new MerchantUserCriteriaTransfer())->fromArray(
            $oauthUserRestrictionRequestTransfer->getUserOrFail()->toArray(),
            true
        );
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createErrorMessage(string $message): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message);
    }
}
