<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Expander\OrderAmendmentsByOrderResourceRelationshipExpander;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Expander\OrderAmendmentsByOrderResourceRelationshipExpanderInterface;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\CartReorderRequestMapper;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\CartReorderRequestMapperInterface;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\OrderAmendmentsMapper;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\OrderAmendmentsMapperInterface;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\RestCartAttributesMapper;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\RestCartAttributesMapperInterface;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\RestResponseBuilder\OrderAmendmentsRestResponseBuilder;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\RestResponseBuilder\OrderAmendmentsRestResponseBuilderInterface;

class OrderAmendmentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\RestCartAttributesMapperInterface
     */
    public function createRestCartAttributesMapper(): RestCartAttributesMapperInterface
    {
        return new RestCartAttributesMapper();
    }

    /**
     * @return \Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\CartReorderRequestMapperInterface
     */
    public function createCartReorderRequestMapper(): CartReorderRequestMapperInterface
    {
        return new CartReorderRequestMapper();
    }

    /**
     * @return \Spryker\Glue\OrderAmendmentsRestApi\Processor\Expander\OrderAmendmentsByOrderResourceRelationshipExpanderInterface
     */
    public function createOrderAmendmentsByOrderResourceRelationshipExpander(): OrderAmendmentsByOrderResourceRelationshipExpanderInterface
    {
        return new OrderAmendmentsByOrderResourceRelationshipExpander($this->createOrderAmendmentsRestResponseBuilder());
    }

    /**
     * @return \Spryker\Glue\OrderAmendmentsRestApi\Processor\RestResponseBuilder\OrderAmendmentsRestResponseBuilderInterface
     */
    public function createOrderAmendmentsRestResponseBuilder(): OrderAmendmentsRestResponseBuilderInterface
    {
        return new OrderAmendmentsRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createOrderAmendmentsMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\OrderAmendmentsMapperInterface
     */
    public function createOrderAmendmentsMapper(): OrderAmendmentsMapperInterface
    {
        return new OrderAmendmentsMapper();
    }
}
