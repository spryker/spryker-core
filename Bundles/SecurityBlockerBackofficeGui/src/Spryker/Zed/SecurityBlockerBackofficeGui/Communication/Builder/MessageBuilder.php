<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Builder;

use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;
use Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Facade\SecurityBlockerBackofficeGuiToGlossaryFacadeInterface;

class MessageBuilder implements MessageBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ACCOUNT_BLOCKED = 'security_blocker_backoffice_gui.error.account_blocked';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_MINUTES = '%minutes%';

    /**
     * @var \Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Facade\SecurityBlockerBackofficeGuiToGlossaryFacadeInterface
     */
    protected SecurityBlockerBackofficeGuiToGlossaryFacadeInterface $glossaryFacade;

    /**
     * @param \Spryker\Zed\SecurityBlockerBackofficeGui\Dependency\Facade\SecurityBlockerBackofficeGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(SecurityBlockerBackofficeGuiToGlossaryFacadeInterface $glossaryFacade)
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
