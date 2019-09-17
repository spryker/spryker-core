<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Factory;

use Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventItemTriggerFormDataProvider;
use Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventTriggerFormDataProvider;
use Symfony\Component\Form\FormInterface;

interface StateMachineTriggerFormFactoryInterface
{
    /**
     * @return \Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventTriggerFormDataProvider
     */
    public function createEventTriggerFormDataProvider(): EventTriggerFormDataProvider;

    /**
     * @return \Spryker\Zed\StateMachine\Communication\Form\DataProvider\EventItemTriggerFormDataProvider
     */
    public function createEventItemTriggerFormDataProvider(): EventItemTriggerFormDataProvider;

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventTriggerForm(): FormInterface;

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventItemTriggerForm(): FormInterface;

    /**
     * @param int $identifier
     * @param string $redirect
     * @param string $stateMachineName
     * @param string $processName
     * @param string $eventName
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFullFilledEventItemTriggerForm(
        int $identifier,
        string $redirect,
        string $stateMachineName,
        string $processName,
        string $eventName
    ): FormInterface;

    /**
     * @param int $identifier
     * @param string $redirect
     * @param int $idState
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFullFilledEventTriggerForm(
        int $identifier,
        string $redirect,
        int $idState,
        string $event
    ): FormInterface;
}
