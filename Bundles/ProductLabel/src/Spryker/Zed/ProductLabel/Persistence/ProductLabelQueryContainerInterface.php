<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

interface ProductLabelQueryContainerInterface
{
    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::getAllProductLabelsSortedByPosition()} instead.
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelsSortedByPosition();

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::findProductLabelById} instead.
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelById($idProductLabel);

    /**
     * @api
     *
     * @depreacted Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::findProductLabelByName} instead.
     *
     * @param string $labelName
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($labelName);

    /**
     * @api
     *
     * @depreacted Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::getProductLabelsByIdProductAbstract} instead.
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductsLabelByIdProductAbstract($idProductAbstract);

    /**
     * @api
     *
     * @depreacted Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::getActiveProductLabelIdsByIdProductAbstract} instead.
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryActiveProductsLabelByIdProductAbstract($idProductAbstract);

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryLocalizedAttributesByIdProductLabel($idProductLabel);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryAllLocalizedAttributesLabels();

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductLabel
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryLocalizedAttributesByIdProductLabelAndIdLocale($idProductLabel, $idLocale);

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryMaxPosition();

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductAbstractRelationsByIdProductLabel($idProductLabel);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryAllProductLabelProductAbstractRelations();

    /**
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductAbstractRelationsByIdProductLabelAndIdsProductAbstract(
        $idProductLabel,
        array $idsProductAbstract
    );

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryUnpublishedProductLabelsBecomingValid();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryPublishedProductLabelsBecomingInvalid();

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryValidProductLabelsByIdProductAbstract($idProductAbstract);
}
