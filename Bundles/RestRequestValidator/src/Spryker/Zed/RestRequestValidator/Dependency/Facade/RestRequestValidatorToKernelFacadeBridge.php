<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Dependency\Facade;

class RestRequestValidatorToKernelFacadeBridge implements RestRequestValidatorToKernelFacadeInterface
{
    /**
     * @var \Spryker\Zed\Kernel\Business\KernelFacadeInterface
     */
    protected $kernelFacade;

    /**
     * @param \Spryker\Zed\Kernel\Business\KernelFacadeInterface $KernelFacade
     */
    public function __construct($KernelFacade)
    {
        $this->kernelFacade = $KernelFacade;
    }

    /**
     * @return array<string>
     */
    public function getCodeBuckets(): array
    {
        return $this->kernelFacade->getCodeBuckets();
    }
}
