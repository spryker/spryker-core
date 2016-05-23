<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Business\Tree\Exception\NodeNotFoundException;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class NodeWriter implements NodeWriterInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     */
    public function __construct(CategoryQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return int
     */
    public function create(NodeTransfer $categoryNode)
    {
        $nodeEntity = $this->queryContainer
            ->queryMainNodesByCategoryId($categoryNode->getIdCategoryNode())
            ->findOneOrCreate();

        $nodeEntity->fromArray($categoryNode->toArray());
        $nodeEntity->save();

        $nodeId = $nodeEntity->getIdCategoryNode();
        $categoryNode->setIdCategoryNode($nodeId);

        return $nodeId;
    }

    /**
     * @param int $nodeId
     *
     * @throws \Spryker\Zed\Category\Business\Tree\Exception\NodeNotFoundException
     *
     * @return int
     */
    public function delete($nodeId)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($nodeId)
            ->findOne();

        if (!$nodeEntity) {
            throw new NodeNotFoundException();
        }

        $categoryId = $nodeEntity->getFkCategory();
        $nodeEntity->delete();

        return $categoryId;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNode
     *
     * @return void
     */
    public function update(NodeTransfer $categoryNode)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($categoryNode->getIdCategoryNode())
            ->findOne();

        if ($nodeEntity) {
            $nodeEntity->fromArray($categoryNode->toArray());
            $nodeEntity->save();
        }
    }

}
