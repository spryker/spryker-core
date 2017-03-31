<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiMetaTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Business\Model\Validator\ApiValidatorInterface;

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

        $resource = $apiRequestTransfer->getResource();
        $method = $apiRequestTransfer->getResourceAction();
        $params = $apiRequestTransfer->getResourceParameters();

        $apiResponseTransfer = new ApiResponseTransfer();

        try {
            $errors = $this->getValidationErrors($apiRequestTransfer);

            if ($errors) {
                $apiResponseTransfer->setCode(422);
                $apiResponseTransfer->setMessage('Validation errors.');
                $apiResponseTransfer->setValidationErrors(new ArrayObject($errors));
                $apiResponseTransfer->setData($errors); //TODO remove
            } else {
                $apiCollectionOrItem = $this->callApiPlugin($resource, $method, $params);
                $data = (array)$apiCollectionOrItem->getData();
                $apiResponseTransfer->setData($data);

                if ($apiCollectionOrItem instanceof ApiCollectionTransfer) {
                    $apiResponseTransfer->setPagination($apiCollectionOrItem->getPagination());
                    if (!$apiResponseTransfer->getMeta()) {
                        $apiResponseTransfer->setMeta(new ApiMetaTransfer());
                    }
                } elseif ($apiCollectionOrItem instanceof ApiItemTransfer) {
                    if (!$apiResponseTransfer->getMeta()) {
                        $apiResponseTransfer->setMeta(new ApiMetaTransfer());
                    }
                    $apiResponseTransfer->getMeta()->setResourceId($apiCollectionOrItem->getId());
                }
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

        $apiResponseTransfer = $this->processor->postProcess($apiRequestTransfer, $apiResponseTransfer);

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
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer|\Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function callApiPlugin($resource, $method, $params)
    {
        return $this->resourceHandler->execute($resource, $method, $params);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer[]
     */
    protected function getValidationErrors(ApiRequestTransfer $apiRequestTransfer)
    {
        if (!$apiRequestTransfer->getData()) {
            return [];
        }

        return $this->validator->validate(
            $apiRequestTransfer->getResource(),
            $apiRequestTransfer->getData()
        );
    }

}
