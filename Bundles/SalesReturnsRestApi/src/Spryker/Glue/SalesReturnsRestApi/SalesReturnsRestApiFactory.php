<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnSearchClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilder;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnResponseBuilder;
use Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnResponseBuilderInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Expander\ReturnItemExpander;
use Spryker\Glue\SalesReturnsRestApi\Processor\Expander\ReturnItemExpanderInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapper;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapper;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReader;
use Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReaderInterface;
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
            $this->getSalesReturnSearchClient(),
            $this->createRestReturnReasonResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Expander\ReturnItemExpanderInterface
     */
    public function createReturnItemExpander(): ReturnItemExpanderInterface
    {
        return new ReturnItemExpander(
            $this->createRestReturnResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Writer\ReturnWriterInterface
     */
    public function createReturnWriter(): ReturnWriterInterface
    {
        return new ReturnWriter(
            $this->getSalesReturnClient(),
            $this->createRestReturnResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReaderInterface
     */
    public function createReturnReader(): ReturnReaderInterface
    {
        return new ReturnReader(
            $this->getSalesReturnClient(),
            $this->createRestReturnResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnResponseBuilderInterface
     */
    public function createRestReturnResponseBuilder(): RestReturnResponseBuilderInterface
    {
        return new RestReturnResponseBuilder(
            $this->getResourceBuilder(),
            $this->createReturnResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Builder\RestReturnReasonResponseBuilderInterface
     */
    public function createRestReturnReasonResponseBuilder(): RestReturnReasonResponseBuilderInterface
    {
        return new RestReturnReasonResponseBuilder(
            $this->getResourceBuilder(),
            $this->createReturnReasonResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface
     */
    public function createReturnReasonResourceMapper(): ReturnReasonResourceMapperInterface
    {
        return new ReturnReasonResourceMapper();
    }

    /**
     * @return \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface
     */
    public function createReturnResourceMapper(): ReturnResourceMapperInterface
    {
        return new ReturnResourceMapper(
            $this->getConfig()
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
     * @return \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnSearchClientInterface
     */
    public function getSalesReturnSearchClient(): SalesReturnsRestApiToSalesReturnSearchClientInterface
    {
        return $this->getProvidedDependency(SalesReturnsRestApiDependencyProvider::CLIENT_SALES_RETURN_SEARCH);
    }
}
