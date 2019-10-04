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
     * @var \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface[]
     */
    protected $preProcessStack;

    /**
     * @var \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface[]
     */
    protected $postProcessStack;

    /**
     * @param \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface[] $preProcessStack
     * @param \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface[] $postProcessStack
     */
    public function __construct(array $preProcessStack, array $postProcessStack)
    {
        $this->preProcessStack = $preProcessStack;
        $this->postProcessStack = $postProcessStack;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function preProcess(ApiRequestTransfer $apiRequestTransfer)
    {
        foreach ($this->preProcessStack as $preProcessor) {
            $apiRequestTransfer = $preProcessor->process($apiRequestTransfer);
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
        foreach ($this->postProcessStack as $postProcessor) {
            $apiResponseTransfer = $postProcessor->process($apiRequestTransfer, $apiResponseTransfer);
        }

        return $apiResponseTransfer;
    }
}
