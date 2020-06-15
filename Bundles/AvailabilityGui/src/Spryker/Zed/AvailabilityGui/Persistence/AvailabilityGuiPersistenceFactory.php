<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Persistence;

use Spryker\Zed\AvailabilityGui\AvailabilityGuiDependencyProvider;
use Spryker\Zed\AvailabilityGui\Persistence\Expander\ProductAbstractAvailabilityQueryExpander;
use Spryker\Zed\AvailabilityGui\Persistence\Expander\ProductAbstractAvailabilityQueryExpanderInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AvailabilityGui\AvailabilityGuiConfig getConfig()
 * @method \Spryker\Zed\AvailabilityGui\Persistence\AvailabilityGuiRepositoryInterface getRepository()
 */
class AvailabilityGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityGui\Persistence\Expander\ProductAbstractAvailabilityQueryExpanderInterface
     */
    public function createProductAbstractAvailabilityQueryExpander(): ProductAbstractAvailabilityQueryExpanderInterface
    {
        return new ProductAbstractAvailabilityQueryExpander(
            $this->getAvailabilityAbstractQueryCriteriaExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityAbstractQueryCriteriaExpanderPluginInterface[]
     */
    public function getAvailabilityAbstractQueryCriteriaExpanderPlugins(): array
    {
        return $this->getProvidedDependency(AvailabilityGuiDependencyProvider::PLUGINS_AVAILABILITY_ABSTRACT_QUERY_CRITERIA_EXPANDER);
    }
}
