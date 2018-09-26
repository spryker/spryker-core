<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request;

use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse;
use Spryker\Zed\Ratepay\Business\Exception\NoMethodMapperException;
use Spryker\Zed\Ratepay\Business\Log\LoggerTrait;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;

abstract class TransactionHandlerAbstract implements TransactionHandlerInterface
{
    use LoggerTrait;

    public const TRANSACTION_TYPE = null;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory
     */
    protected $converterFactory;

    /**
     * @var \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $methodMappers = [];

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory $converterFactory
     * @param \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface $queryContainer
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        ConverterFactory $converterFactory,
        RatepayQueryContainerInterface $queryContainer
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->converterFactory = $converterFactory;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse
     */
    protected function sendRequest($request)
    {
        return new BaseResponse($this->executionAdapter->sendRequest($request));
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Request\RequestMethodInterface $mapper
     *
     * @return void
     */
    public function registerMethodMapper(RequestMethodInterface $mapper)
    {
        $this->methodMappers[$mapper->getMethodName()] = $mapper;
    }

    /**
     * @param string $method
     *
     * @throws \Spryker\Zed\Ratepay\Business\Exception\NoMethodMapperException
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\MethodInterface|\Spryker\Zed\Ratepay\Business\Request\Service\Method\MethodInterface
     */
    protected function getMethodMapper($method)
    {
        if (isset($this->methodMappers[$method]) === false) {
            throw new NoMethodMapperException(sprintf("The method %s mapper is not registered.", $method));
        }

        return $this->methodMappers[$method];
    }
}
