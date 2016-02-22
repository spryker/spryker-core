<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

use Spryker\Zed\Cms\Business\CmsFacade;

class ProductCategoryToCmsBridge implements ProductCategoryToCmsInterface
{

    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacade
     */
    protected $cmsFacade;

    /**
     * ProductCategoryToCmsBridge constructor.
     *
     * @param \Spryker\Zed\Cms\Business\CmsFacade $cmsFacade
     */
    public function __construct($cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode)
    {
        $this->cmsFacade->updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);
    }

    /**
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode)
    {
        return $this->cmsFacade->getCmsBlocksByIdCategoryNode($idCategoryNode);
    }

}
