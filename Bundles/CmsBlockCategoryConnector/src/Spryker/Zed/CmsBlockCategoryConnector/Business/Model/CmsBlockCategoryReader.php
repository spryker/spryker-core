<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
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

        $idCategories = $this->queryContainer
            ->queryCmsBlockCategoryConnectorByIdCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->find()
            ->getColumnValues('FkCategory');

        $cmsBlockTransfer->setIdCategories($idCategories);

        return $cmsBlockTransfer;
    }

    /**
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return string[]
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

}
