<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataFeed\Dependency\QueryContainer;

use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryDataFeedToCategoryBridge implements CategoryDataFeedToCategoryInterface
{

    /**
     * @var CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * CategoryDataFeedToCategoryBridge constructor.
     *
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct($categoryQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale)
    {
        return $this->categoryQueryContainer
            ->queryCategory($idLocale);
    }
}
