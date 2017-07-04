<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Spryker\Shared\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConstants;
use Spryker\Zed\CmsBlockCategoryConnector\Business\Exception\CmsBlockCategoryPositionNotFound;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToTouchInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface;
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
     * @var
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToTouchInterface $touchFacade
     * @param CmsBlockCategoryConnectorToCategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $queryContainer,
        CmsBlockCategoryConnectorToTouchInterface $touchFacade,
        CmsBlockCategoryConnectorToCategoryQueryContainerInterface $categoryQueryContainer
    ) {
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
        $this->categoryQueryContainer = $categoryQueryContainer;
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
     * @param CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($categoryTransfer) {
            $this->updateCategoryCmsBlockRelationsTransaction($categoryTransfer);
        });
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function updateCategoryCmsBlockRelationsTransaction(CategoryTransfer $categoryTransfer)
    {
        $this->deleteCategoryCmsBlockRelations($categoryTransfer);
        $this->createCategoryCmsBlockRelations($categoryTransfer);
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function deleteCategoryCmsBlockRelations(CategoryTransfer $categoryTransfer)
    {
        $categoryTransfer->requireIdCategory();

        $query = $this->queryContainer
            ->queryCmsBlockCategoryConnectorByIdCategory($categoryTransfer->getIdCategory());

        $this->deleteRelations($query);
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function createCategoryCmsBlockRelations(CategoryTransfer $categoryTransfer)
    {
        $categoryTransfer->requireIdCategory();

        foreach ($categoryTransfer->getIdCmsBlocks() as $positionKey => $idCmsBlocks) {
            $this->createRelations($idCmsBlocks, [$categoryTransfer->getIdCategory()], $positionKey);
        }
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

        $this->deleteRelations($query);
    }

    /**
     * @param SpyCmsBlockCategoryConnectorQuery $query
     *
     * @return void
     */
    protected function deleteRelations(SpyCmsBlockCategoryConnectorQuery $query)
    {
        foreach ($query->find() as $relation) {
            $relation->delete();

            $this->touchFacade->touchDeleted(
                CmsBlockCategoryConnectorConstants::RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR,
                $relation->getFkCategory()
            );
        }
    }

    /**
     * @param array $idCmsBlocks
     * @param array $idCategories
     * @param string $positionKey
     *
     * @return void
     */
    protected function createRelations(array $idCmsBlocks, array $idCategories, $positionKey)
    {
        $spyCmsBlockCategoryPosition = $this->getPositionByKey($positionKey);

        foreach ($idCategories as $idCategory) {
            $spyCategory = $this->getCategoryById($idCategory);

            foreach ($idCmsBlocks as $idCmsBlock) {
                $this->createRelation(
                    $idCmsBlock,
                    $idCategory,
                    $spyCmsBlockCategoryPosition->getIdCmsBlockCategoryPosition(),
                    $spyCategory->getFkCategoryTemplate()
                );
            }

            $this->touchFacade->touchActive(
                CmsBlockCategoryConnectorConstants::RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR,
                $idCategory
            );
        }
    }

    /**
     * @param int $idCmsBlock
     * @param int $idCategory
     * @param int $idCmsBlockCategoryPosition
     * @param int $idCategoryTemplate
     *
     * @return void
     */
    protected function createRelation($idCmsBlock, $idCategory, $idCmsBlockCategoryPosition, $idCategoryTemplate)
    {
        $spyCmsBlockConnector = $this->createaBlockCategoryConnectorEntity();
        $spyCmsBlockConnector
            ->setFkCmsBlock($idCmsBlock)
            ->setFkCategory($idCategory)
            ->setFkCmsBlockCategoryPosition($idCmsBlockCategoryPosition)
            ->setFkCategoryTemplate($idCategoryTemplate)
            ->save();
    }

    /**
     * @param string $positionKey
     *
     * @throws CmsBlockCategoryPositionNotFound
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPosition
     */
    protected function getPositionByKey($positionKey)
    {
        $spyCmsBlockCategoryPosition = $this->queryContainer
            ->queryCmsBlockCategoryPositionByKey($positionKey)
            ->findOne();

        if (empty($spyCmsBlockCategoryPosition)) {
            throw new CmsBlockCategoryPositionNotFound();
        }

        return $spyCmsBlockCategoryPosition;
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategoryById($idCategory)
    {
        return $this->categoryQueryContainer
            ->queryCategoryById($idCategory)
            ->findOne();
    }


    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function createCmsBlockConnectorRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        foreach ($cmsBlockTransfer->getIdCategories() as $positionKey => $idCategories) {
            $this->createRelations([$cmsBlockTransfer->getIdCmsBlock()], $idCategories, $positionKey);
        }
    }

    /**
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector
     */
    protected function createaBlockCategoryConnectorEntity()
    {
        return new SpyCmsBlockCategoryConnector();
    }

}
