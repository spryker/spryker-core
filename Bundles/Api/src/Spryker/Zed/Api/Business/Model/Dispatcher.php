<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface;

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
     * @var \Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\Api\Business\Model\ResourceHandlerInterface $resourceHandler
     * @param \Spryker\Zed\Api\Business\Model\ProcessorInterface $processor
     * @param \Spryker\Zed\Api\Business\Model\Validator\ValidatorInterface $validator
     */
    public function __construct(
        ResourceHandlerInterface $resourceHandler,
        ProcessorInterface $processor,
        ValidatorInterface $validator
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
        $params = $apiRequestTransfer->getResourceParams();

        $apiResponseTransfer = new ApiResponseTransfer();

        // right now can also be transfer
        try {
            $errors = $this->validator->validate($apiRequestTransfer);
            if ($errors) {
                $apiResponseTransfer->setCode(422);
                $apiResponseTransfer->setMessage('Validation errors.');
                $apiResponseTransfer->setValidationErrors($errors);
            } else {
                $apiCollectionOrItem = $this->callApiPlugin($resource, $method, $params);
                $data = (array)$apiCollectionOrItem->modifiedToArray(true);
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

}
