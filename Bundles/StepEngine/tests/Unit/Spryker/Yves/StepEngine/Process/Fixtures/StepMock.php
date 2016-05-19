<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Process\Fixtures;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Symfony\Component\HttpFoundation\Request;

class StepMock implements StepInterface
{

    /**
     * @var bool
     */
    private $postCondition;

    /**
     * @var bool
     */
    private $preCondition;

    /**
     * @var bool
     */
    private $requireInput;

    /**
     * @var string
     */
    private $stepRoute;

    /**
     * @var string
     */
    private $escapeRoute;

    /**
     * @var string
     */
    private $externalUrl;

    /**
     * @param bool $preCondition
     * @param bool $postCondition
     * @param bool $requireInput
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct($preCondition = true, $postCondition = true, $requireInput = true, $stepRoute = '', $escapeRoute = '', $externalUrl = '')
    {
        $this->preCondition = $preCondition;
        $this->postCondition = $postCondition;
        $this->requireInput = $requireInput;
        $this->stepRoute = $stepRoute;
        $this->escapeRoute = $escapeRoute;
        $this->externalUrl = $externalUrl;
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $dataTransfer)
    {
        return $this->preCondition;
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer)
    {
        return $this->requireInput;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer $transfer
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $transfer)
    {
        return $transfer;
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $dataTransfer)
    {
        return $this->postCondition;
    }

    /**
     * @return string
     */
    public function getStepRoute()
    {
        return $this->stepRoute;
    }

    /**
     * @return string
     */
    public function getEscapeRoute()
    {
        return $this->escapeRoute;
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer)
    {
        return [];
    }

}
