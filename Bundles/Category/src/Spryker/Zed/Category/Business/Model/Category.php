<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface;
use Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface;
use Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface;
use Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface;
use Spryker\Zed\Category\Business\Model\Category\CategoryInterface;

class Category
{

    /**
     * @var \Spryker\Zed\Category\Business\Model\Category\CategoryInterface
     */
    protected $category;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface
     */
    protected $categoryNode;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface
     */
    protected $categoryAttribute;

    /**
     * @var \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface
     */
    protected $categoryUrl;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Spryker\Zed\Category\Business\Model\Category\CategoryInterface $category
     * @param \Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNodeInterface $categoryNode
     * @param \Spryker\Zed\Category\Business\Model\CategoryAttribute\CategoryAttributeInterface $categoryAttribute
     * @param \Spryker\Zed\Category\Business\Model\CategoryUrl\CategoryUrlInterface $categoryUrl
     * @param \Spryker\Zed\Category\Business\Model\CategoryExtraParents\CategoryExtraParentsInterface $categoryExtraParents
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     */
    public function __construct(
        CategoryInterface $category,
        CategoryNodeInterface $categoryNode,
        CategoryAttributeInterface $categoryAttribute,
        CategoryUrlInterface $categoryUrl,
        CategoryExtraParentsInterface $categoryExtraParents,
        ConnectionInterface $connection
    ) {
        $this->category = $category;
        $this->categoryNode = $categoryNode;
        $this->categoryAttribute = $categoryAttribute;
        $this->categoryUrl = $categoryUrl;
        $this->categoryExtraParents = $categoryExtraParents;
        $this->connection = $connection;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $this->connection->beginTransaction();

        $this->category->create($categoryTransfer);
        $this->categoryNode->create($categoryTransfer);
        $this->categoryAttribute->create($categoryTransfer);
        $this->categoryUrl->create($categoryTransfer);
        $this->categoryExtraParents->create($categoryTransfer);

        $this->connection->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $this->connection->beginTransaction();

        $this->category->update($categoryTransfer);
        $this->categoryNode->update($categoryTransfer);
        $this->categoryAttribute->update($categoryTransfer);
        $this->categoryUrl->update($categoryTransfer);
        $this->categoryExtraParents->update($categoryTransfer);

        $this->connection->commit();
    }

}
