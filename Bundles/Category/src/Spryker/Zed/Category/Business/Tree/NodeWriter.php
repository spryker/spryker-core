<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class NodeWriter implements NodeWriterInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryToucherInterface
     */
    protected $categoryToucher;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucherInterface $categoryToucher
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryToucherInterface $categoryToucher
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryToucher = $categoryToucher;
    }

    /**
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateOrder($idCategoryNode, $position)
    {
        $categoryNodeEntity = $this
            ->queryContainer
            ->queryNodeById($idCategoryNode)
            ->findOne();

        if ($categoryNodeEntity) {
            $categoryNodeEntity->setNodeOrder($position);
            $categoryNodeEntity->save();

            $this->categoryToucher->touchCategoryNodeActive($categoryNodeEntity->getIdCategoryNode());
        }
    }
}
