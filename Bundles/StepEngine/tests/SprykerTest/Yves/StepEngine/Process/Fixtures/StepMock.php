<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Process\Fixtures;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
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
     * @param bool $preCondition
     * @param bool $postCondition
     * @param bool $requireInput
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct($preCondition = true, $postCondition = true, $requireInput = true, $stepRoute = '', $escapeRoute = '')
    {
        $this->preCondition = $preCondition;
        $this->postCondition = $postCondition;
        $this->requireInput = $requireInput;
        $this->stepRoute = $stepRoute;
        $this->escapeRoute = $escapeRoute;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        return $this->preCondition;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer)
    {
        return $this->requireInput;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $dataTransfer)
    {
        return $dataTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer)
    {
        return [];
    }
}
