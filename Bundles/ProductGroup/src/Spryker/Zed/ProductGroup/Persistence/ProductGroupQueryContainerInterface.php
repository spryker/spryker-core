<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Persistence;

interface ProductGroupQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idProductGroup
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery
     */
    public function queryProductGroupById($idProductGroup);

    /**
     * @api
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryAllProductAbstractGroups();

    /**
     * @api
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery
     */
    public function queryAllProductGroups();

    /**
     * @api
     *
     * @param int $idProductGroup
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupsById($idProductGroup);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int|null $excludedIdProductGroup
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupsByIdProductAbstract($idProductAbstract, $excludedIdProductGroup = null);
}
