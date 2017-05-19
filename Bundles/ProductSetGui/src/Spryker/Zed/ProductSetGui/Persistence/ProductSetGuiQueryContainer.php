<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiPersistenceFactory getFactory()
 */
class ProductSetGuiQueryContainer extends AbstractQueryContainer implements ProductSetGuiQueryContainerInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(LocaleTransfer $localeTransfer)
    {
        $query = $this->getFactory()
            ->createProductAbstractQuery()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractByIdProductSet($idProductSet, LocaleTransfer $localeTransfer)
    {
        $query = $this->getFactory()
            ->createProductAbstractQuery()
            ->useSpyProductAbstractSetQuery()
                ->filterByFkProductSet($idProductSet)
            ->endUse()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse();

        return $query;
    }
}
