<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Step;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class AbstractBaseStep implements StepInterface
{
    /**
     * @var string
     */
    protected $stepRoute;

    /**
     * @var string
     */
    protected $escapeRoute;

    /**
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct($stepRoute, $escapeRoute)
    {
        $this->stepRoute = $stepRoute;
        $this->escapeRoute = $escapeRoute;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $dataTransfer)
    {
        return [];
    }
}
