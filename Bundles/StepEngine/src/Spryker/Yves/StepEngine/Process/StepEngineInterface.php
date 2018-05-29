<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

interface StepEngineInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function process(Request $request, ?FormCollectionHandlerInterface $formCollection = null);
}
