<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleInterface;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerInterface;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;

class CmsBlockProductDataProvider
{
    public const PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME = 'name';

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface
     */
    protected $cmsBlockProductQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerInterface
     */
    protected $productAbstractQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductQueryContainer
     * @param \Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerInterface $productAbstractQueryContainer
     * @param \Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\CmsBlockProductConnectorToLocaleInterface $localeFacade
     */
    public function __construct(
        CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductQueryContainer,
        CmsBlockProductConnectorToProductAbstractQueryContainerInterface $productAbstractQueryContainer,
        CmsBlockProductConnectorToLocaleInterface $localeFacade
    ) {
        $this->cmsBlockProductQueryContainer = $cmsBlockProductQueryContainer;
        $this->productAbstractQueryContainer = $productAbstractQueryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsBlockTransfer::class,
            CmsBlockProductAbstractType::OPTION_PRODUCT_ABSTRACT_ARRAY => $this->getProductAbstractArray(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function getData(CmsBlockTransfer $cmsBlockTransfer)
    {
        $idProductAbstracts = [];

        if ($cmsBlockTransfer->getIdCmsBlock()) {
            $idProductAbstracts = $this->getAssignedProductAbstractArray($cmsBlockTransfer->getIdCmsBlock());
        }

        $cmsBlockTransfer->setIdProductAbstracts($idProductAbstracts);

        return $cmsBlockTransfer;
    }

    /**
     * @return array
     */
    protected function getProductAbstractArray()
    {
        $idLocale = $this->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();

        $productAbstracts = $this->productAbstractQueryContainer
            ->queryProductAbstractWithName($idLocale)
            ->find();

        $productAbstractArray = [];

        foreach ($productAbstracts as $spyProductAbstract) {
            $label = $spyProductAbstract->getVirtualColumn(static::PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME) .
                ' (SKU: ' . $spyProductAbstract->getSku() . ')';

            $productAbstractArray[$label] = $spyProductAbstract->getIdProductAbstract();
        }

        return $productAbstractArray;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return array
     */
    protected function getAssignedProductAbstractArray($idCmsBlock)
    {
        return $this->cmsBlockProductQueryContainer
            ->queryCmsBlockProductConnectorByIdCmsBlock($idCmsBlock)
            ->find()
            ->getColumnValues('fkProductAbstract');
    }
}
