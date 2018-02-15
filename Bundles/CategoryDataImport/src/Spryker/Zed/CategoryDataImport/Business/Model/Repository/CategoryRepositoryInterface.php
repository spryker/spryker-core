<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Model\Repository;

use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;

interface CategoryRepositoryInterface
{
    /**
     * @param string $categoryKey
     *
     * @return bool
     */
    public function hasCategory($categoryKey);

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return void
     */
    public function addCategory(SpyCategory $categoryEntity, SpyCategoryNode $categoryNodeEntity);

    /**
     * @param string $categoryKey
     *
     * @throws \Spryker\Zed\CategoryDataImport\Business\Exception\CategoryByKeyNotFoundException
     *
     * @return int
     */
    public function getIdCategoryNodeByCategoryKey($categoryKey);

    /**
     * @param string $categoryKey
     *
     * @throws \Spryker\Zed\CategoryDataImport\Business\Exception\CategoryByKeyNotFoundException
     *
     * @return int
     */
    public function getIdCategoryByCategoryKey($categoryKey);

    /**
     * @param string $categoryKey
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\CategoryDataImport\Business\Exception\CategoryByKeyNotFoundException
     *
     * @return string
     */
    public function getParentUrl($categoryKey, $idLocale);
}
