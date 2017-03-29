<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;

class Processor implements ProcessorInterface
{

    /**
     * @var \Spryker\Zed\Api\Dependency\Plugin\ApiPreProcessorPluginInterface[]
     */
    protected $preProcessPluginStack;

    /**
     * @var \Spryker\Zed\Api\Dependency\Plugin\ApiPostProcessorPluginInterface[]
     */
    protected $postProcessPluginStack;

    /**
     * @param Processor\Pre\PreProcessorInterface[] $preProcessStack
     * @param Processor\Post\PostProcessorInterface[] $postProcessStack
     */
    public function __construct(array $preProcessStack, array $postProcessStack)
    {
        $this->preProcessPluginStack = $preProcessStack;
        $this->postProcessPluginStack = $postProcessStack;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     */
    public function preProcess(ApiRequestTransfer $apiRequestTransfer)
    {
        foreach ($this->preProcessPluginStack as $preProcessor) {
            $preProcessor->process($apiRequestTransfer);
        }

        return $apiRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function postProcess(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        foreach ($this->postProcessPluginStack as $postProcessor) {
            $postProcessor->process($apiRequestTransfer, $apiResponseTransfer);
        }

        return $apiResponseTransfer;
    }

}
