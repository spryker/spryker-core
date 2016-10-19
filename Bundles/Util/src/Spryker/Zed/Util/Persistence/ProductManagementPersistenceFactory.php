<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Util\Persistence;

use Orm\Zed\Util\Persistence\Base\SpyUtilAttributeValueTranslationQuery;
use Orm\Zed\Util\Persistence\SpyUtilAttributeQuery;
use Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery;
use Orm\Zed\Product\Persistence\Base\SpyProductAttributeKeyQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Util\UtilConfig getConfig()
 * @method \Spryker\Zed\Util\Persistence\UtilQueryContainer getQueryContainer()
 */
class UtilPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeQuery
     */
    public function createUtilAttributeQuery()
    {
        return SpyUtilAttributeQuery::create();
    }

    /**
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery
     */
    public function createUtilAttributeValueQuery()
    {
        return SpyUtilAttributeValueQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function createProductAttributeKeyQuery()
    {
        return SpyProductAttributeKeyQuery::create();
    }

    /**
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueTranslationQuery
     */
    public function createUtilAttributeValueTranslationQuery()
    {
        return SpyUtilAttributeValueTranslationQuery::create();
    }

}
