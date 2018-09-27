<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Renderer;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

/**
 * @deprecated Will be removed with next major release
 */
class CategoryTreeRenderer
{
    public const NODE_HASH_ALGORITHM = 'sha256';
    public const UNKNOWN_CATEGORY = 'Unknown Category';

    /**
     * @var int
     */
    protected $fontSize = 11;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var \Spryker\Shared\Graph\GraphInterface
     */
    protected $graph;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Shared\Graph\GraphInterface $graph
     */
    public function __construct(CategoryQueryContainerInterface $queryContainer, LocaleTransfer $locale, GraphInterface $graph)
    {
        $this->queryContainer = $queryContainer;
        $this->locale = $locale;
        $this->graph = $graph;
    }

    /**
     * @return string|false
     */
    public function render()
    {
        $rootNode = $this->queryContainer->queryRootNode()->findOne();
        if ($rootNode) {
            $this->renderChildren($rootNode);

            return $this->graph->render('svg');
        }

        return false;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     *
     * @return void
     */
    protected function renderChildren(SpyCategoryNode $node)
    {
        $children = $this->queryContainer
            ->queryFirstLevelChildrenByIdLocale($node->getPrimaryKey(), $this->locale->getIdLocale())
            ->find();

        $deleteLink = '/category-tree/index/delete-node?id=';
        $deleteString = '<br/>click to delete';
        $this->addClosureConnections($node);
        $this->graph->addNode(
            $this->getNodeHash($node),
            [
                'URL' => $deleteLink . $node->getPrimaryKey(),
                'label' => $this->getNodeName($node) . $deleteString,
                'fontsize' => $this->fontSize,
            ]
        );
        foreach ($children as $child) {
            $this->graph->addNode(
                $this->getNodeHash($child),
                [
                    'URL' => $deleteLink . $child->getPrimaryKey(),
                    'label' => $this->getNodeName($child) . $deleteString,
                    'fontsize' => $this->fontSize,
                ]
            );
            $this->graph->addEdge($this->getNodeHash($node), $this->getNodeHash($child));
            /*
             * Recursive call
             */
            $this->renderChildren($child);
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     *
     * @return string
     */
    protected function getNodeName(SpyCategoryNode $node)
    {
        return $this->getLocalizedCategoryName($node->getCategory()) . ' (' . $node->getPrimaryKey() . ')';
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     *
     * @return string
     */
    protected function getNodeHash(SpyCategoryNode $node)
    {
        return hash(static::NODE_HASH_ALGORITHM, $this->getNodeName($node));
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $node
     *
     * @return void
     */
    protected function addClosureConnections(SpyCategoryNode $node)
    {
        $descendantPaths = $this->queryContainer->queryDescendant($node->getPrimaryKey())->find();

        foreach ($descendantPaths as $path) {
            $attributes = ['color' => '#ff0000'];
            $this->graph->addEdge($this->getNodeHash($node), $this->getNodeHash($path->getDescendantNode()), $attributes);
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return string
     */
    protected function getLocalizedCategoryName(SpyCategory $categoryEntity)
    {
        foreach ($categoryEntity->getAttributes() as $attributeEntity) {
            return $attributeEntity->getName();
        }

        return self::UNKNOWN_CATEGORY;
    }
}
