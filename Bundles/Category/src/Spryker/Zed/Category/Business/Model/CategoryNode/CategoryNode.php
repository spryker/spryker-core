<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryNode;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface;

class CategoryNode implements CategoryNodeInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $closureTableWriter;

    /**
     * @param \Spryker\Zed\Category\Business\Tree\ClosureTableWriterInterface $closureTableWriter
     */
    public function __construct(ClosureTableWriterInterface $closureTableWriter)
    {
        $this->closureTableWriter = $closureTableWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $categoryNodeEntity = new SpyCategoryNode();
        $categoryNodeEntity->fromArray($categoryTransfer->toArray());
        $categoryNodeEntity->setFkCategory($categoryTransfer->getIdCategory());
        $categoryNodeEntity->setFkParentCategoryNode($categoryTransfer->getParent()->getFkParentCategoryNode());
        $categoryNodeEntity->save();

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->fromArray($categoryNodeEntity->toArray(), true);

        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $this->closureTableWriter->create($categoryNodeTransfer);
    }

}
