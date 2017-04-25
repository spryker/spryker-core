<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Dependency\Persistence;

use Generated\Shared\Transfer\CategoryDataFeedTransfer;

class FactFinderToCategoryDataFeedBridge implements FactFinderToCategoryDataFeedInterface
{

    /**
     * @var \Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedQueryContainerInterface
     */
    protected $categoryDataFeedQueryContainer;

    /**
     * FactFinderToCategoryDataFeedBridge constructor.
     *
     * @param \Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedQueryContainerInterface $categoryDataFeedQueryContainer
     */
    public function __construct($categoryDataFeedQueryContainer)
    {
        $this->categoryDataFeedQueryContainer = $categoryDataFeedQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryDataFeedTransfer $categoryDataFeedTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryDataFeed(CategoryDataFeedTransfer $categoryDataFeedTransfer)
    {
        return $this->categoryDataFeedQueryContainer
            ->queryCategoryDataFeed($categoryDataFeedTransfer);
    }

}
