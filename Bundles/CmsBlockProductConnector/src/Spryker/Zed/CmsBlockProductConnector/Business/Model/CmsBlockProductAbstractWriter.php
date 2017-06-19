<?php

namespace Spryker\Zed\CmsBlockProductConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnector;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockProductAbstractWriter implements CmsBlockProductAbstractWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var CmsBlockProductConnectorQueryContainerInterface
     */
    protected $cmsBlockProductConnectorQueryContainer;

    /**
     * @param CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductConnectorQueryContainer
     */
    public function __construct(CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductConnectorQueryContainer)
    {
        $this->cmsBlockProductConnectorQueryContainer = $cmsBlockProductConnectorQueryContainer;
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void;
     */
    public function updateCmsBlockProductAbstractRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($cmsBlockTransfer){
            $this->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);
        });
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     */
    protected function updateCmsBlockProductAbstractRelationsTransaction(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->deleteRelations($cmsBlockTransfer);
        $this->createRelations($cmsBlockTransfer);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function deleteRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $relations = $this->cmsBlockProductConnectorQueryContainer
            ->queryCmsBlockProductConnectorByIdCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->find();

        foreach ($relations as $relation) {
            $relation->delete();
        }
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function createRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        foreach ($cmsBlockTransfer->getIdProductAbstracts() as $idProductAbstract) {
            $spyCmsBlockProductConnector = new SpyCmsBlockProductConnector();
            $spyCmsBlockProductConnector->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
            $spyCmsBlockProductConnector->setFkProductAbstract($idProductAbstract);
            $spyCmsBlockProductConnector->save();
        }
    }

}