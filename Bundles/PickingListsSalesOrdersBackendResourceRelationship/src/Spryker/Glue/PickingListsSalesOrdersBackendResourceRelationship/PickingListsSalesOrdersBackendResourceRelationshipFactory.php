<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource\PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Expander\PickingListsSalesOrdersBackendResourceRelationshipExpander;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Expander\PickingListsSalesOrdersBackendResourceRelationshipExpanderInterface;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilter;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Mapper\PickingListsSalesOrdersBackendResourceRelationshipMapper;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Mapper\PickingListsSalesOrdersBackendResourceRelationshipMapperInterface;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Reader\SalesOrdersResourceReader;
use Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Reader\SalesOrdersResourceReaderInterface;

class PickingListsSalesOrdersBackendResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Expander\PickingListsSalesOrdersBackendResourceRelationshipExpanderInterface
     */
    public function createPickingListsSalesOrdersBackendResourceRelationshipExpander(): PickingListsSalesOrdersBackendResourceRelationshipExpanderInterface
    {
        return new PickingListsSalesOrdersBackendResourceRelationshipExpander(
            $this->createSalesOrdersResourceReader(),
            $this->createPickingListItemResourceFilter(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Reader\SalesOrdersResourceReaderInterface
     */
    public function createSalesOrdersResourceReader(): SalesOrdersResourceReaderInterface
    {
        return new SalesOrdersResourceReader(
            $this->getSalesOrdersBackendApiResource(),
            $this->createPickingListsSalesOrdersBackendResourceRelationshipMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface
     */
    public function createPickingListItemResourceFilter(): PickingListItemResourceFilterInterface
    {
        return new PickingListItemResourceFilter();
    }

    /**
     * @return \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Processor\Mapper\PickingListsSalesOrdersBackendResourceRelationshipMapperInterface
     */
    public function createPickingListsSalesOrdersBackendResourceRelationshipMapper(): PickingListsSalesOrdersBackendResourceRelationshipMapperInterface
    {
        return new PickingListsSalesOrdersBackendResourceRelationshipMapper();
    }

    /**
     * @return \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource\PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface
     */
    public function getSalesOrdersBackendApiResource(): PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface
    {
        return $this->getProvidedDependency(PickingListsSalesOrdersBackendResourceRelationshipDependencyProvider::RESOURCE_SALES_ORDERS_BACKEND_API);
    }
}
