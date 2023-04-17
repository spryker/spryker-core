<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsProductsBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Dependency\Resource\PickingListsProductsBackendResourceRelationshipToProductsBackendApiResourceInterface;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Expander\PickingListItemsBackendResourceRelationshipExpander;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Expander\PickingListItemsBackendResourceRelationshipExpanderInterface;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilter;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Reader\ConcreteProductResourceRelationshipReader;
use Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Reader\ConcreteProductResourceRelationshipReaderInterface;

class PickingListsProductsBackendResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Expander\PickingListItemsBackendResourceRelationshipExpanderInterface
     */
    public function createPickingListItemsBackendResourceRelationshipExpander(): PickingListItemsBackendResourceRelationshipExpanderInterface
    {
        return new PickingListItemsBackendResourceRelationshipExpander(
            $this->createPickingListItemResourceFilter(),
            $this->createConcreteProductResourceRelationshipReader(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Reader\ConcreteProductResourceRelationshipReaderInterface
     */
    public function createConcreteProductResourceRelationshipReader(): ConcreteProductResourceRelationshipReaderInterface
    {
        return new ConcreteProductResourceRelationshipReader(
            $this->getProductBackendApiResource(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface
     */
    public function createPickingListItemResourceFilter(): PickingListItemResourceFilterInterface
    {
        return new PickingListItemResourceFilter();
    }

    /**
     * @return \Spryker\Glue\PickingListsProductsBackendResourceRelationship\Dependency\Resource\PickingListsProductsBackendResourceRelationshipToProductsBackendApiResourceInterface
     */
    public function getProductBackendApiResource(): PickingListsProductsBackendResourceRelationshipToProductsBackendApiResourceInterface
    {
        return $this->getProvidedDependency(PickingListsProductsBackendResourceRelationshipDependencyProvider::RESOURCE_PRODUCTS_BACKEND_API);
    }
}
