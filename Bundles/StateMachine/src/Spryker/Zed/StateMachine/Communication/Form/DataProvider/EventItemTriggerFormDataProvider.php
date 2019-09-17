<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Form\DataProvider;

use Generated\Shared\Transfer\EventItemTriggerFormDataTransfer;
use Spryker\Zed\StateMachine\Communication\Form\EventItemTriggerForm;
use Spryker\Zed\StateMachine\Communication\Form\EventTriggerForm;

class EventItemTriggerFormDataProvider
{
    public const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-order-single-event';

    /**
     * @param int $identifier
     * @param string $redirect
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Generated\Shared\Transfer\EventItemTriggerFormDataTransfer
     */
    public function getData(
        int $identifier,
        string $redirect,
        string $stateMachineName,
        string $processName
    ): EventItemTriggerFormDataTransfer {
        return (new EventItemTriggerFormDataTransfer())
            ->setIdentifier($identifier)
            ->setRedirect($redirect)
            ->setStateMachineName($stateMachineName)
            ->setProcessName($processName);
    }

    /**
     * @param string $eventName
     * @param string $redirect
     *
     * @return array
     */
    public function getOptions(string $eventName, string $redirect): array
    {
        return [
            EventItemTriggerForm::OPTION_EVENT => $eventName,
            EventItemTriggerForm::OPTION_REDIRECT => $redirect,
            EventTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
        ];
    }
}
