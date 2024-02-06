<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Dependency\Facade\AgentSecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface;

class MessageBuilder implements MessageBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ACCOUNT_BLOCKED = 'agent_security_blocker_merchant_portal_gui.error.account_blocked';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_MINUTES = '%minutes%';

    /**
     * @var \Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Dependency\Facade\AgentSecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface
     */
    protected AgentSecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade;

    /**
     * @param \Spryker\Zed\AgentSecurityBlockerMerchantPortalGui\Dependency\Facade\AgentSecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(AgentSecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
     *
     * @return string
     */
    public function getExceptionMessage(SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer): string
    {
        return $this->glossaryFacade->translate(
            static::GLOSSARY_KEY_ERROR_ACCOUNT_BLOCKED,
            [static::GLOSSARY_PARAM_MINUTES => $this->convertSecondsToReadableTime($securityCheckAuthResponseTransfer)],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
     *
     * @return string
     */
    protected function convertSecondsToReadableTime(
        SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
    ): string {
        $seconds = $securityCheckAuthResponseTransfer->getBlockedFor() ?: 0;

        return (string)ceil($seconds / 60);
    }
}
