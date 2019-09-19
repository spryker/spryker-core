<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Builder;

use Spryker\Zed\StateMachine\Communication\Factory\StateMachineTriggerFormFactoryInterface;

class StateMachineTriggerFormCollectionBuilder implements StateMachineTriggerFormCollectionBuilderInterface
{
    /**
     * @var \Spryker\Zed\StateMachine\Communication\Factory\StateMachineTriggerFormFactoryInterface
     */
    protected $stateMachineTriggerFormFactory;

    /**
     * @param \Spryker\Zed\StateMachine\Communication\Factory\StateMachineTriggerFormFactoryInterface $stateMachineTriggerFormFactory
     */
    public function __construct(StateMachineTriggerFormFactoryInterface $stateMachineTriggerFormFactory)
    {
        $this->stateMachineTriggerFormFactory = $stateMachineTriggerFormFactory;
    }

    /**
     * @param int $identifier
     * @param string $redirect
     * @param int $idState
     * @param string[] $events
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function buildEventTriggerFormCollection(
        int $identifier,
        string $redirect,
        int $idState,
        array $events
    ): array {
        $eventTriggerFormCollection = [];

        foreach ($events as $event) {
            $eventTriggerFormCollection[$event] = $this->stateMachineTriggerFormFactory
                ->createFullFilledEventTriggerForm($identifier, $redirect, $idState, $event)
                ->createView();
        }

        return $eventTriggerFormCollection;
    }
}
