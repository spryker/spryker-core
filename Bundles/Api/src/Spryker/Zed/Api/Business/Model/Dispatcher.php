<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\ApiDispatchingException;
use Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface;

class Dispatcher
{

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $apiConfig;

    /**
     * @var array
     */
    protected $preProcessStack;

    /**
     * @var array
     */
    protected $postProcessStack;

    /**
     * @var \Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\Api\ApiConfig $apiConfig
     * @param \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface[] $preProcessStack
     * @param \Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface[] $postProcessStack
     * @param \Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface $validator
     */
    public function __construct(ApiConfig $apiConfig, array $preProcessStack, array $postProcessStack, ValidatorInterface $validator)
    {
        $this->apiConfig = $apiConfig;
        $this->preProcessStack = $preProcessStack;
        $this->postProcessStack = $postProcessStack;
        $this->validator = $validator;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function dispatch(ApiRequestTransfer $apiRequestTransfer)
    {
        $this->preProcess($apiRequestTransfer);

        $resource = $apiRequestTransfer->getResource();
        $method = $apiRequestTransfer->getResourceAction();
        $params = $apiRequestTransfer->getResourceParams();

        $apiResponseTransfer = new ApiResponseTransfer();

        // right now can also be transfer
        try {
            $errors = $this->validator->validate($apiRequestTransfer);
            if ($errors) {
                $apiResponseTransfer->setCode(422);
                $apiResponseTransfer->setMessage('Validation Errors.');
                $apiResponseTransfer->setValidationErrors($errors);
            } else {
                $entityOrCollection = $this->callApiPlugin($resource, $method, $params);
                $data = [];
                if ($entityOrCollection) {
                    $data = $entityOrCollection->modifiedToArray(true);
                }
                $apiResponseTransfer->setData($data);
            }
        } catch (\Exception $e) {
            $apiResponseTransfer->setCode($e->getCode() ?: 500);
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        } catch (\Throwable $e) {
            $apiResponseTransfer->setCode($e->getCode() ?: 500);
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        }

        $this->postProcess($apiRequestTransfer, $apiResponseTransfer);

        if ($apiResponseTransfer->getCode() === null) {
            $apiResponseTransfer->setCode(200);
        }

        return $apiResponseTransfer;
    }

    /**
     * @param string $resource
     * @param string $method
     * @param array $params
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return \Spryker\Zed\Api\Business\Model\ApiCollectionInterface|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function callApiPlugin($resource, $method, $params)
    {
        $pluginClass = $this->apiConfig->getPluginForResource($resource);

        if (!method_exists($pluginClass, $method)) {
            throw new ApiDispatchingException(sprintf('Method %s() not found on Plugin class %s', $method, $pluginClass));
        }

        $plugin = new $pluginClass($this->apiConfig);

        return call_user_func_array([$plugin, $method], $params);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    protected function preProcess(ApiRequestTransfer $apiRequestTransfer)
    {
        foreach ($this->preProcessStack as $preProcessor) {
            if (is_string($preProcessor)) {
                $preProcessor = new $preProcessor($this->apiConfig);
            }
            $preProcessor->process($apiRequestTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return void
     */
    protected function postProcess(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        foreach ($this->postProcessStack as $postProcessor) {
            if (is_string($postProcessor)) {
                $postProcessor = new $postProcessor($this->apiConfig);
            }
            $postProcessor->process($apiRequestTransfer, $apiResponseTransfer);
        }
    }

}
