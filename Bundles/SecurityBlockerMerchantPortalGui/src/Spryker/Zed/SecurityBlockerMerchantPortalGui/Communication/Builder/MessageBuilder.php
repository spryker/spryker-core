<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Facade\SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface;

class MessageBuilder implements MessageBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ACCOUNT_BLOCKED = 'security_blocker_merchant_portal_gui.error.account_blocked';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_MINUTES = '%minutes%';

    /**
     * @var \Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Facade\SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface
     */
    protected SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade;

    /**
     * @param \Spryker\Zed\SecurityBlockerMerchantPortalGui\Dependency\Facade\SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(SecurityBlockerMerchantPortalGuiToGlossaryFacadeInterface $glossaryFacade)
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
