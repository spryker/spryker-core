<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockCategoryWriter
{
    /**
     * @var CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsBlockCategoryConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(CmsBlockCategoryConnectorQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->deleteCmsBlockConnectorRelations($cmsBlockTransfer);
        $this->createCmsBlockConnectorRelations($cmsBlockTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function deleteCmsBlockConnectorRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        $query = $this->queryContainer
            ->queryCmsBlockCategoryConnectorByIdCmsBlock($cmsBlockTransfer->getIdCmsBlock());

        foreach ($query->find() as $relation) {
            $relation->delete();
        }
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function createCmsBlockConnectorRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        foreach ($cmsBlockTransfer->getCategories() as $idCategory) {
            $spyCmsBlockConnector = new SpyCmsBlockCategoryConnector();
            $spyCmsBlockConnector->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
            $spyCmsBlockConnector->setFkCategory($idCategory);
            $spyCmsBlockConnector->save();
        }
    }

}