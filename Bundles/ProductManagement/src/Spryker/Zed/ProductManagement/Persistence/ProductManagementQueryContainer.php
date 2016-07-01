<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementPersistenceFactory getFactory()
 */
class ProductManagementQueryContainer extends AbstractQueryContainer implements ProductManagementQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute()
    {
        return $this->getFactory()->createProductManagementAttributeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalizedQuery
     */
    public function queryProductManagementAttributeLocalized()
    {
        return $this->getFactory()->createProductManagementAttributeLocalizedQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadataQuery
     */
    public function queryProductManagementAttributeMetadata()
    {
        return $this->getFactory()->createProductManagementAttributeMetadataQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInputQuery
     */
    public function queryProductManagementAttributeInput()
    {
        return $this->getFactory()->createProductManagementAttributeInputQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeTypeQuery
     */
    public function queryProductManagementAttributeType()
    {
        return $this->getFactory()->createProductManagementAttributeTypeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue()
    {
        return $this->getFactory()->createProductManagementAttributeValueQuery();
    }

}
