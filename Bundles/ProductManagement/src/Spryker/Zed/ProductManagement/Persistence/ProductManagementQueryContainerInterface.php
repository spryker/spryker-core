<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductManagementQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeLocalizedQuery
     */
    public function queryProductManagementAttributeLocalized();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeMetadataQuery
     */
    public function queryProductManagementAttributeMetadata();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeInputQuery
     */
    public function queryProductManagementAttributeInput();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeTypeQuery
     */
    public function queryProductManagementAttributeType();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue();

}
