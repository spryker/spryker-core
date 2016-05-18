<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process\Steps;

use Spryker\Shared\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\Request;

interface StepInterface
{

    /**
     * @return AbstractTransfer
     */
    public function getDataClass();

    /**
     * Requirements for this step, return true when satisfied.
     *
     * @return bool
     */
    public function preCondition();

    /**
     * Require input, should we render view with form or just skip step after calling execute.
     *
     * @return bool
     */
    public function requireInput();

    /**
     * Execute step logic, happens after form submit if provided, gets AbstractTransfer filled by form data.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer $transfer
     *
     * @return void
     */
    public function execute(Request $request, AbstractTransfer $transfer = null);

    /**
     * Conditions that should be met for this step to be marked as completed. returns true when satisfied.
     *
     * @return bool
     */
    public function postCondition();

    /**
     * Current step route.
     *
     * @return string
     */
    public function getStepRoute();

    /**
     * Escape route when preConditions are not satisfied user will be redirected to provided route.
     *
     * @return string
     */
    public function getEscapeRoute();

    /**
     * @return array
     */
    public function getTemplateVariables();

}
