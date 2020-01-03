<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Business\Model\CategoryToucherInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class NodeWriter implements NodeWriterInterface
{
    /**
     * @deprecated This is not in use anymore
     */
    public const CATEGORY_URL_IDENTIFIER_LENGTH = 4;

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
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return int
     */
    public function create(NodeTransfer $categoryNodeTransfer)
    {
        $categoryNodeEntity = new SpyCategoryNode();
        $categoryNodeEntity->fromArray($categoryNodeTransfer->toArray());
        $categoryNodeEntity->save();

        $idCategoryNode = $categoryNodeEntity->getIdCategoryNode();
        $categoryNodeTransfer->setIdCategoryNode($idCategoryNode);

        return $idCategoryNode;
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     *
     * @return bool
     */
    public function delete($idCategoryNode)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($idCategoryNode)
            ->findOne();

        if ($nodeEntity) {
            $nodeEntity->delete();
        }

        return $nodeEntity->isDeleted();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return void
     */
    public function update(NodeTransfer $categoryNodeTransfer)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($categoryNodeTransfer->getIdCategoryNode())
            ->findOne();

        if ($nodeEntity) {
            $nodeEntity->fromArray($categoryNodeTransfer->toArray());
            $nodeEntity->save();
        }
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
