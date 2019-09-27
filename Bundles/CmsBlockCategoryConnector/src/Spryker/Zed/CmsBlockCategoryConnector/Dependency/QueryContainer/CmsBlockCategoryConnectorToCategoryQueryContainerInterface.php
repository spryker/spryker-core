<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer;

interface CmsBlockCategoryConnectorToCategoryQueryContainerInterface
{
    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryById($idCategory);

    /**
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplateById($idCategoryTemplate);

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplate();
}
