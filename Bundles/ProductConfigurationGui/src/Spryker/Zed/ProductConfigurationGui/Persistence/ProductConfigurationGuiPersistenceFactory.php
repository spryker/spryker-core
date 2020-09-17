<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductConfigurationGui\ProductConfigurationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationGui\Persistence\ProductConfigurationGuiRepositoryInterface getRepository()
 */
class ProductConfigurationGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function getProductConfigurationPropelQuery()
    {
        return $this->getProvidedDependency(ProductConfigurationGuiDependencyProvider::PROPEL_QUERY_PRODUCT_CONFIGURATION);
    }
}
