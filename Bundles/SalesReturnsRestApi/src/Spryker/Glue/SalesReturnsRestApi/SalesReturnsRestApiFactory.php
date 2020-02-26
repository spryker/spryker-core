<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapper;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnReasonResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReasonReader;
use Spryker\Glue\SalesReturnsRestApi\Processor\Reader\ReturnReasonReaderInterface;

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
}
