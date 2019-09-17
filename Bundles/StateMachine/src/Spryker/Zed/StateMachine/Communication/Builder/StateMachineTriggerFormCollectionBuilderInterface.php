<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Communication\Builder;

interface StateMachineTriggerFormCollectionBuilderInterface
{
    /**
     * @param int $identifier
     * @param string $redirect
     * @param int $idState
     * @param array $events
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function buildEventTriggerFormCollection(
        int $identifier,
        string $redirect,
        int $idState,
        array $events
    ): array;
}
