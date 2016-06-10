<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductSearchQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperationQuery
     */
    public function queryFieldOperations();

    /**
     * @api
     *
     * @deprecated Not used. Will be removed in the next major release.
     *
     * @param array $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperationQuery
     */
    public function queryAttributeOperation($idAttribute, $copyTarget);

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery
     */
    public function queryByProductAndLocale($idProduct, $idLocale);

}
