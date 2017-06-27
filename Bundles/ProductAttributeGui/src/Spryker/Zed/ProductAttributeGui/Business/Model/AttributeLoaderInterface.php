<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

use Propel\Runtime\ActiveQuery\Criteria;

interface AttributeLoaderInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $query
     *
     * @return array
     */
    public function load(Criteria $query);

    /**
     * @param array $productAttributes
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria|\Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryMetaAttributes(array $productAttributes);

    /**
     * @param array $productAttributes
     * @param bool $isSuper
     *
     * @return \Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueTranslationQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryProductAttributeValues(array $productAttributes = [], $isSuper = false);

}
