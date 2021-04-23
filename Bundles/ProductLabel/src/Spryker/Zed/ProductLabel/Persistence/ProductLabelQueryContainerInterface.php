<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

interface ProductLabelQueryContainerInterface
{
    /**
     * Specification:
     * - Creates a query to find product labels sorted by a position.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::getAllProductLabelsSortedByPosition()} instead.
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelsSortedByPosition();

    /**
     * Specification:
     * - Creates a query to find a product label by it's id.
     *
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
     * Specification:
     * - Creates a query to find a product label by it's name.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::findProductLabelByName} instead.
     *
     * @param string $labelName
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($labelName);

    /**
     * Specification:
     * - Creates a query to find product labels by a product abstract id.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::getProductLabelsByIdProductAbstract} instead.
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductsLabelByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Creates a query to find active product labels by a product abstract id.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::getActiveProductLabelIdsByIdProductAbstract} instead.
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryActiveProductsLabelByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryAllLocalizedAttributesLabels();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryMaxPosition();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductAbstractRelationsByIdProductLabel($idProductLabel);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryAllProductLabelProductAbstractRelations();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface::getProductAbstractRelationsByIdProductLabelAndProductAbstractIds()} instead.
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryUnpublishedProductLabelsBecomingValid();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryPublishedProductLabelsBecomingInvalid();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryValidProductLabelsByIdProductAbstract($idProductAbstract);
}
