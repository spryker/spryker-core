<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantAgent\Business\Checker;

use Exception;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MerchantAgentAclAccessChecker implements MerchantAgentAclAccessCheckerInterface
{
    use LoggerTrait;

    /**
     * @uses \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig::ROLE_MERCHANT_AGENT
     *
     * @var string
     */
    protected const ROLE_MERCHANT_AGENT = 'ROLE_MERCHANT_AGENT';

    /**
     * @var \Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig
     */
    protected AclMerchantAgentConfig $aclMerchantAgentConfig;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * @param \Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig $aclMerchantAgentConfig
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        AclMerchantAgentConfig $aclMerchantAgentConfig,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->aclMerchantAgentConfig = $aclMerchantAgentConfig;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function isApplicable(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool
    {
        try {
            return $userTransfer->getIsMerchantAgent() === true
                && $this->authorizationChecker->isGranted(static::ROLE_MERCHANT_AGENT);
        } catch (Exception $exception) {
            $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);

            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @return bool
     */
    public function checkAccess(UserTransfer $userTransfer, RuleTransfer $ruleTransfer): bool
    {
        return in_array($ruleTransfer->getBundleOrFail(), $this->aclMerchantAgentConfig->getMerchantAgentAclBundleAllowedList());
    }
}
