<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Util\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface UtilQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeQuery
     */
    public function queryUtilAttribute();

    /**
     * @api
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery
     */
    public function queryUtilAttributeValue();

    /**
     * @api
     *
     * @param int $idUtilAttribute
     * @param int $idLocale
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery
     */
    public function queryUtilAttributeValueWithTranslation($idUtilAttribute, $idLocale);

    /**
     * @api
     *
     * @param int $idUtilAttribute
     * @param int $idLocale
     * @param string|null $attributeValueOrTranslation
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryFindAttributeByValueOrTranslation($idUtilAttribute, $idLocale, $attributeValueOrTranslation = null);

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * @api
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueQuery
     */
    public function queryUtilAttributeValueQuery();

    /**
     * @api
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueTranslationQuery
     */
    public function queryUtilAttributeValueTranslation();

    /**
     * @api
     *
     * @param int $idUtilAttribute
     *
     * @return \Orm\Zed\Util\Persistence\SpyUtilAttributeValueTranslationQuery
     */
    public function queryUtilAttributeValueTranslationById($idUtilAttribute);

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryUnusedProductAttributeKeys();

}
