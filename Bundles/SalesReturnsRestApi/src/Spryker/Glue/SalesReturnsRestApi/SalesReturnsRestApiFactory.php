<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\RestApiResource\SalesReturnsRestApiToOrdersRestApiResourceInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapper;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapper;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReasonReader;
use Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReasonReaderInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Writer\ReturnWriter;
use Spryker\Glue\SalesReturnsRestApi\Processor\Writer\ReturnWriterInterface;

/**
 * @method \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig getConfig()
 */
class SalesReturnsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReasonReaderInterface
     */
    public function createReturnReasonReader(): ReturnReasonReaderInterface
    {
        return new ReturnReasonReader(
            $this->getSalesReturnClient(),
            $this->getResourceBuilder(),
            $this->createReturnReasonResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface
     */
    public function createReturnReasonResourceMapper(): ReturnReasonResourceMapperInterface
    {
        return new ReturnReasonResourceMapper(
            $this->getGlossaryStorageClient()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Writer\ReturnWriterInterface
     */
    public function createReturnWriter(): ReturnWriterInterface
    {
        return new ReturnWriter(
            $this->getSalesReturnClient(),
            $this->getResourceBuilder(),
            $this->createReturnResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface
     */
    public function createReturnResourceMapper(): ReturnResourceMapperInterface
    {
        return new ReturnResourceMapper(
            $this->getConfig(),
            $this->getOrdersRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface
     */
    public function getSalesReturnClient(): SalesReturnsRestApiToSalesReturnClientInterface
    {
        return $this->getProvidedDependency(SalesReturnsRestApiDependencyProvider::CLIENT_SALES_RETURN);
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): SalesReturnsRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SalesReturnsRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Dependency\RestApiResource\SalesReturnsRestApiToOrdersRestApiResourceInterface
     */
    public function getOrdersRestApiResource(): SalesReturnsRestApiToOrdersRestApiResourceInterface
    {
        return $this->getProvidedDependency(SalesReturnsRestApiDependencyProvider::RESOURCE_ORDERS_REST_API);
    }
}
