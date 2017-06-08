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
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelsSortedByPosition();

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelById($idProductLabel);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductsLabelByIdProductAbstract($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryLocalizedAttributesByIdProductLabel($idProductLabel);

    /**
     * @api
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

}
