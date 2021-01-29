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
    protected $postCondition;

    /**
     * @var bool
     */
    protected $preCondition;

    /**
     * @var bool
     */
    protected $requireInput;

    /**
     * @var string
     */
    protected $stepRoute;

    /**
     * @var string|null
     */
    protected $escapeRoute;

    /**
     * @param bool $preCondition
     * @param bool $postCondition
     * @param bool $requireInput
     * @param string $stepRoute
     * @param string|null $escapeRoute
     */
    public function __construct(
        bool $preCondition = true,
        bool $postCondition = true,
        bool $requireInput = true,
        string $stepRoute = '',
        ?string $escapeRoute = null
    ) {
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
    public function preCondition(AbstractTransfer $quoteTransfer): bool
    {
        return $this->preCondition;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer): bool
    {
        return $this->requireInput;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer): bool
    {
        return $this->postCondition;
    }

    /**
     * @return string
     */
    public function getStepRoute(): string
    {
        return $this->stepRoute;
    }

    /**
     * @return string|null
     */
    public function getEscapeRoute(): ?string
    {
        return $this->escapeRoute;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer): array
    {
        return [];
    }
}
