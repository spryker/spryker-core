<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Persistence;

interface ProductCategoryFilterGuiQueryContainerInterface
{
    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryByIdAndLocale($idCategory, $idLocale);

    /**
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryFilterSuggestions($searchTerm, $idLocale);
}
