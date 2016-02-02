<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

use Spryker\Zed\Cms\Business\CmsFacade;
use Generated\Shared\Transfer\CmsBlockTransfer;

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
     * @return CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode)
    {
        return $this->cmsFacade->getCmsBlocksByIdCategoryNode($idCategoryNode);
    }

}
