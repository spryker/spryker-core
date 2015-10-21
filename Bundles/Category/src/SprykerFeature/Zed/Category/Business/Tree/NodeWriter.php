<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Category\Business\Tree\Exception\NodeNotFoundException;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNode;

class NodeWriter implements NodeWriterInterface
{

    const CATEGORY_URL_IDENTIFIER_LENGTH = 4;

    /**
     * @var CategoryQueryContainer
     */
    protected $queryContainer;

    /**
     * @param CategoryQueryContainer $queryContainer
     */
    public function __construct(CategoryQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @throws PropelException
     *
     * @return int
     */
    public function create(NodeTransfer $categoryNode)
    {
        $nodeEntity = new SpyCategoryNode();
        $nodeEntity->fromArray($categoryNode->toArray());
        $nodeEntity->save();

        $nodeId = $nodeEntity->getIdCategoryNode();
        $categoryNode->setIdCategoryNode($nodeId);

        return $nodeId;
    }

    /**
     * @param int $nodeId
     *
     * @throws NodeNotFoundException
     * @throws PropelException
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
     * @param NodeTransfer $categoryNode
     *
     * @return void
     *
     * @throws PropelException
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
