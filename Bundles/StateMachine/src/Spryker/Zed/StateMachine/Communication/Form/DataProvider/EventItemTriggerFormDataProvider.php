<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Form\DataProvider;

use Spryker\Zed\StateMachine\Communication\Form\EventItemTriggerForm;
use Spryker\Zed\StateMachine\Communication\Form\EventTriggerForm;

class EventItemTriggerFormDataProvider
{
    protected const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-order-single-event';

    protected const URL_PARAM_IDENTIFIER = 'identifier';
    protected const URL_PARAM_STATE_MACHINE_NAME = 'state-machine-name';
    protected const URL_PARAM_PROCESS_NAME = 'process-name';
    protected const URL_PARAM_REDIRECT = 'redirect';
    protected const URL_PARAM_EVENTS = 'events';

    /**
     * @param int $identifier
     * @param string $redirect
     * @param string $eventName
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return array
     */
    public function getOptions(
        int $identifier,
        string $redirect,
        string $eventName,
        string $stateMachineName,
        string $processName
    ): array {
        return [
            EventItemTriggerForm::OPTION_EVENT => $eventName,
            EventItemTriggerForm::OPTION_ACTION_QUERY_PARAMETERS => [
                static::URL_PARAM_IDENTIFIER => $identifier,
                static::URL_PARAM_REDIRECT => $redirect,
                static::URL_PARAM_STATE_MACHINE_NAME => $stateMachineName,
                static::URL_PARAM_PROCESS_NAME => $processName,
            ],
            EventTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
        ];
    }
}
