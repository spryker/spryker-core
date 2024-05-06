<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;

class CmsBlockProductAbstractReader implements CmsBlockProductAbstractReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface $queryContainer
     */
    public function __construct(
        CmsBlockProductConnectorQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function hydrateProductRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        /** @var \Propel\Runtime\Collection\ObjectCollection $cmsBlockProductConnectorCollection */
        $cmsBlockProductConnectorCollection = $this->queryContainer
            ->queryCmsBlockProductConnectorByIdCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->find();

        $idProductAbstracts = $cmsBlockProductConnectorCollection->getColumnValues('FkProductAbstract');

        $cmsBlockTransfer->setIdProductAbstracts($idProductAbstracts);

        return $cmsBlockTransfer;
    }

    /**
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return array<string>
     */
    public function getProductAbstractRenderedList($idCmsBlock, $idLocale)
    {
        $productAbstracts = $this->queryContainer
            ->queryCmsBlockProductConnectorWithNameByIdCmsBlock($idCmsBlock, $idLocale)
            ->find();

        $productAbstractList = [];

        foreach ($productAbstracts as $spyProductAbstract) {
            $productAbstractList[$spyProductAbstract->getFkProductAbstract()] =
                $spyProductAbstract->getVirtualColumn(CmsBlockProductConnectorQueryContainerInterface::COL_PRODUCT_ABSTRACT_NAME) .
                ' (SKU: ' . $spyProductAbstract->getVirtualColumn(CmsBlockProductConnectorQueryContainerInterface::COL_PRODUCT_ABSTRACT_SKU) . ')';
        }

        return $productAbstractList;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<string>
     */
    public function getCmsBlockRenderedList($idProductAbstract)
    {
        $relations = $this->queryContainer
            ->queryCmsBlockProductConnectorByIdProductAbstract($idProductAbstract)
            ->find();

        $cmsBlockList = [];
        foreach ($relations as $relation) {
                $cmsBlockList[] = $relation->getCmsBlock()->getName();
        }

        return $cmsBlockList;
    }
}
