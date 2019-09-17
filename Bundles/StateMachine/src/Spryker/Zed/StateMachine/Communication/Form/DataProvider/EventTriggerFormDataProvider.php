<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Form\DataProvider;

use Generated\Shared\Transfer\EventTriggerFormDataTransfer;
use Spryker\Zed\StateMachine\Communication\Form\EventTriggerForm;

class EventTriggerFormDataProvider
{
    public const SUBMIT_BUTTON_CLASS = 'btn btn-primary btn-sm trigger-event';

    /**
     * @param int $identifier
     * @param string $redirect
     * @param int $idState
     * @param string $event
     *
     * @return \Generated\Shared\Transfer\EventTriggerFormDataTransfer
     */
    public function getData(
        int $identifier,
        string $redirect,
        int $idState,
        string $event
    ): EventTriggerFormDataTransfer {
        return (new EventTriggerFormDataTransfer())->setEvent($event)
            ->setIdentifier($identifier)
            ->setIdState($idState)
            ->setRedirect($redirect);
    }

    /**
     * @param string $event
     * @param string $redirect
     *
     * @return array
     */
    public function getOptions(string $event, string $redirect): array
    {
        return [
            EventTriggerForm::OPTION_EVENT => $event,
            EventTriggerForm::OPTION_REDIRECT => $redirect,
            EventTriggerForm::OPTION_SUBMIT_BUTTON_CLASS => static::SUBMIT_BUTTON_CLASS,
        ];
    }
}
