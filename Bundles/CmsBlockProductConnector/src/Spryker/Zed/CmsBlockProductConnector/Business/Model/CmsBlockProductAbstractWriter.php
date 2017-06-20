<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnector;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockProductAbstractWriter implements CmsBlockProductAbstractWriterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface
     */
    protected $cmsBlockProductConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductConnectorQueryContainer
     */
    public function __construct(CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductConnectorQueryContainer)
    {
        $this->cmsBlockProductConnectorQueryContainer = $cmsBlockProductConnectorQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlockProductAbstractRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($cmsBlockTransfer) {
            $this->updateCmsBlockProductAbstractRelationsTransaction($cmsBlockTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function updateCmsBlockProductAbstractRelationsTransaction(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->deleteRelations($cmsBlockTransfer);
        $this->createRelations($cmsBlockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
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
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
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
