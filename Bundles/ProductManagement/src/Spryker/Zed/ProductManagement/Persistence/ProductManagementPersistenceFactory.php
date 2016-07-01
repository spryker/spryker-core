<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInputQuery;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalizedQuery;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadataQuery;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeQuery;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeTypeQuery;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 */
class ProductManagementPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeQuery
     */
    public function createProductManagementAttributeQuery()
    {
        return SpyProductManagementAttributeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalizedQuery
     */
    public function createProductManagementAttributeLocalizedQuery()
    {
        return SpyProductManagementAttributeLocalizedQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadataQuery
     */
    public function createProductManagementAttributeMetadataQuery()
    {
        return SpyProductManagementAttributeMetadataQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInputQuery
     */
    public function createProductManagementAttributeInputQuery()
    {
        return SpyProductManagementAttributeInputQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeTypeQuery
     */
    public function createProductManagementAttributeTypeQuery()
    {
        return SpyProductManagementAttributeTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function createProductManagementAttributeValueQuery()
    {
        return SpyProductManagementAttributeValueQuery::create();
    }

}
