<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiPersistenceFactory getFactory()
 */
class CategoryGuiRepository extends AbstractRepository implements CategoryGuiRepositoryInterface
{
    protected const ID_CATEGORY_TEMPLATE = 'idCategoryTemplate';
    protected const CATEGORY_TEMPLATE_NAME = 'name';

    /**
     * @param string $categoryKey
     * @param int $idCategory
     *
     * @return bool
     */
    public function isCategoryKeyUsed(string $categoryKey, int $idCategory): bool
    {
        return $this->getFactory()
            ->getCategoryPropelQuery()
            ->filterByCategoryKey($categoryKey)
            ->filterByIdCategory($idCategory, Criteria::NOT_EQUAL)
            ->count() > 0;
    }

    /**
     * @return string[]
     */
    public function getIndexedCategoryTemplateNames(): array
    {
        return $this->getFactory()
            ->getCategoryTemplatePropelQuery()
            ->find()
            ->toKeyValue(static::ID_CATEGORY_TEMPLATE, static::CATEGORY_TEMPLATE_NAME);
    }
}
