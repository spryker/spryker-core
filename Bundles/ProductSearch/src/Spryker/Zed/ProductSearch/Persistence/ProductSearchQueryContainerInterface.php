<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductSearchQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperationQuery
     */
    public function queryFieldOperations();

    /**
     * @param array $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, LocaleTransfer $locale);

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperationQuery
     */
    public function queryAttributeOperation($idAttribute, $copyTarget);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale);

}
