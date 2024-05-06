<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockCategoryReader implements CmsBlockCategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function hydrateCategoryRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        /** @var \Propel\Runtime\Collection\ObjectCollection $cmsBlockCategoryCollection */
        $cmsBlockCategoryCollection = $this->queryContainer
            ->queryCmsBlockCategoryConnectorByIdCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->find();

        $idCategories = $cmsBlockCategoryCollection->getColumnValues('FkCategory');

        $cmsBlockTransfer->setIdCategories($idCategories);

        return $cmsBlockTransfer;
    }

    /**
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return array<string>
     */
    public function getRenderedCategoryList($idCmsBlock, $idLocale)
    {
        $categoryConnections = $this->queryContainer
            ->queryCmsBlockCategoryWithNamesByIdBlock($idCmsBlock, $idLocale)
            ->find();

        $categoryList = [];
        foreach ($categoryConnections as $categoryConnection) {
            $categoryList[] = $categoryConnection->getCategory()
                ->getLocalisedAttributes($idLocale)
                ->getFirst()
                ->getName();
        }

        return $categoryList;
    }

    /**
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return array<\Generated\Shared\Transfer\CmsBlockTransfer>
     */
    public function getCmsBlockCollection($idCategory, $idCategoryTemplate)
    {
        $relations = $this->queryContainer
            ->queryCmsBlockCategoryWithBlocksByIdCategoryIdTemplate($idCategory, $idCategoryTemplate)
            ->find();

        $cmsBlockTransfers = [];

        foreach ($relations as $relation) {
            $cmsBlockTransfer = new CmsBlockTransfer();
            $cmsBlockTransfer = $this->mapCmsBlockEntityToTransfer($relation->getCmsBlock(), $cmsBlockTransfer);

            $cmsBlockTransfers[] = $cmsBlockTransfer;
        }

        return $cmsBlockTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array<string>
     */
    public function getCmsBlockNamesIndexedByCmsBlockIdsForCategory(CategoryTransfer $categoryTransfer): array
    {
        $cmsBlocks = [];

        $cmsBlockTransfers = $this->getCmsBlockCollection(
            $categoryTransfer->getIdCategoryOrFail(),
            $categoryTransfer->getFkCategoryTemplateOrFail(),
        );

        foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
            $cmsBlocks[$cmsBlockTransfer->getIdCmsBlock()] = $cmsBlockTransfer->getName();
        }

        return $cmsBlocks;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    protected function mapCmsBlockEntityToTransfer(SpyCmsBlock $spyCmsBlock, CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->fromArray($spyCmsBlock->toArray(), true);

        return $cmsBlockTransfer;
    }
}
