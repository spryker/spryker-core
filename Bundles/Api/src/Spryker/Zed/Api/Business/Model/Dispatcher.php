<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiMetaTransfer;
use Generated\Shared\Transfer\ApiOptionsTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface;
use Throwable;

class Dispatcher implements DispatcherInterface
{
    /**
     * @var \Spryker\Zed\Api\Business\Model\ResourceHandlerInterface
     */
    protected $resourceHandler;

    /**
     * @var \Spryker\Zed\Api\Business\Model\ProcessorInterface
     */
    protected $processor;

    /**
     * @var \Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\Api\Business\Model\ResourceHandlerInterface $resourceHandler
     * @param \Spryker\Zed\Api\Business\Model\ProcessorInterface $processor
     * @param \Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface $validator
     */
    public function __construct(
        ResourceHandlerInterface $resourceHandler,
        ProcessorInterface $processor,
        ApiValidatorInterface $validator
    ) {
        $this->resourceHandler = $resourceHandler;
        $this->processor = $processor;
        $this->validator = $validator;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function dispatch(ApiRequestTransfer $apiRequestTransfer)
    {
        $apiRequestTransfer = $this->processor->preProcess($apiRequestTransfer);

        $apiResponseTransfer = $this->dispatchToResource($apiRequestTransfer);

        $apiResponseTransfer = $this->processor->postProcess($apiRequestTransfer, $apiResponseTransfer);

        if ($apiResponseTransfer->getCode() === null) {
            $apiResponseTransfer->setCode(ApiConfig::HTTP_CODE_SUCCESS);
        }

        return $apiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    protected function dispatchToResource(ApiRequestTransfer $apiRequestTransfer)
    {
        $resource = $apiRequestTransfer->getResource();
        $method = $apiRequestTransfer->getResourceAction();
        $id = $apiRequestTransfer->getResourceId() ? (string)$apiRequestTransfer->getResourceId() : null;
        $params = $apiRequestTransfer->getResourceParameters();

        $apiResponseTransfer = new ApiResponseTransfer();

        try {
            $errors = $this->getValidationErrors($apiRequestTransfer);

            if ($errors) {
                $apiResponseTransfer->setCode(ApiConfig::HTTP_CODE_VALIDATION_ERRORS);
                $apiResponseTransfer->setMessage('Validation errors.');
                $apiResponseTransfer->setValidationErrors(new ArrayObject($errors));
            } else {
                $apiPluginCallResponseTransfer = $this->callApiPlugin($resource, $method, $id, $params);
                $apiResponseTransfer->setType(get_class($apiPluginCallResponseTransfer));
                $apiResponseTransfer->setOptions($apiPluginCallResponseTransfer->getOptions());

                if ($apiPluginCallResponseTransfer instanceof ApiOptionsTransfer) {
                    return $apiResponseTransfer;
                }

                $data = (array)$apiPluginCallResponseTransfer->getData();
                $apiResponseTransfer->setData($data);

                if ($apiPluginCallResponseTransfer instanceof ApiCollectionTransfer) {
                    $apiResponseTransfer->setPagination($apiPluginCallResponseTransfer->getPagination());
                    if (!$apiResponseTransfer->getMeta()) {
                        $apiResponseTransfer->setMeta(new ApiMetaTransfer());
                    }
                } elseif ($apiPluginCallResponseTransfer instanceof ApiItemTransfer) {
                    if (!$apiResponseTransfer->getMeta()) {
                        $apiResponseTransfer->setMeta(new ApiMetaTransfer());
                    }
                    $apiResponseTransfer->getMeta()->setResourceId($apiPluginCallResponseTransfer->getId());
                }
            }
        } catch (Exception $e) {
            $apiResponseTransfer->setCode($this->resolveStatusCode($e->getCode()));
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        } catch (Throwable $e) {
            $apiResponseTransfer->setCode($this->resolveStatusCode($e->getCode()));
            $apiResponseTransfer->setMessage($e->getMessage());
            $apiResponseTransfer->setStackTrace(get_class($e) . ' (' . $e->getFile() . ', line ' . $e->getLine() . '): ' . $e->getTraceAsString());
        }

        return $apiResponseTransfer;
    }

    /**
     * @param int $code
     *
     * @return int
     */
    protected function resolveStatusCode($code)
    {
        if ($code < ApiConfig::HTTP_CODE_SUCCESS || $code > ApiConfig::HTTP_CODE_INTERNAL_ERROR) {
            return ApiConfig::HTTP_CODE_INTERNAL_ERROR;
        }

        return $code;
    }

    /**
     * @param string $resource
     * @param string $method
     * @param string|null $id
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer|\Generated\Shared\Transfer\ApiItemTransfer|\Generated\Shared\Transfer\ApiOptionsTransfer
     */
    protected function callApiPlugin($resource, $method, $id, array $params)
    {
        return $this->resourceHandler->execute($resource, $method, $id, $params);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer[]
     */
    protected function getValidationErrors(ApiRequestTransfer $apiRequestTransfer)
    {
        $resourceParameters = $apiRequestTransfer->getResourceParameters();

        $apiDataTransfer = null;
        foreach ($resourceParameters as $resourceParameter) {
            if (!$resourceParameter instanceof ApiDataTransfer) {
                continue;
            }

            $apiDataTransfer = $resourceParameter;
            break;
        }

        if ($apiDataTransfer === null) {
            return [];
        }

        return $this->validator->validate(
            $apiRequestTransfer->getResource(),
            $apiDataTransfer
        );
    }
}
