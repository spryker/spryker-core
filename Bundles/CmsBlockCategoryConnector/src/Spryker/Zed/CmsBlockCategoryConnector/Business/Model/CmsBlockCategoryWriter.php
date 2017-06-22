<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector;
use Spryker\Shared\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConstants;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToTouchInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockCategoryWriter implements CmsBlockCategoryWriterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToTouchInterface $touchFacade
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $queryContainer,
        CmsBlockCategoryConnectorToTouchInterface $touchFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($cmsBlockTransfer) {
            $this->updateCmsBlockCategoryRelationsTransaction($cmsBlockTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function updateCmsBlockCategoryRelationsTransaction(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->deleteCmsBlockConnectorRelations($cmsBlockTransfer);
        $this->createCmsBlockConnectorRelations($cmsBlockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
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

            $this->touchFacade->touchActive(
                CmsBlockCategoryConnectorConstants::RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR,
                $relation->getFkCategory()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function createCmsBlockConnectorRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        foreach ($cmsBlockTransfer->getIdCategories() as $idCategory) {
            $spyCmsBlockConnector = $this->createaBlockCategoryConnectorEntity();
            $spyCmsBlockConnector->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
            $spyCmsBlockConnector->setFkCategory($idCategory);
            $spyCmsBlockConnector->save();

            $this->touchFacade->touchActive(
                CmsBlockCategoryConnectorConstants::RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR,
                $idCategory
            );
        }
    }

    /**
     * @return SpyCmsBlockCategoryConnector
     */
    protected function createaBlockCategoryConnectorEntity()
    {
        return new SpyCmsBlockCategoryConnector();
    }

}
