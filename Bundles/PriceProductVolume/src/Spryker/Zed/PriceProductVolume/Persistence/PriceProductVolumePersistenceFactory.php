<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductVolume\PriceProductVolumeDependencyProvider;

class PriceProductVolumePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getPropelProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(PriceProductVolumeDependencyProvider::PROPEL_QUERY_PRODUCT);
    }
}
