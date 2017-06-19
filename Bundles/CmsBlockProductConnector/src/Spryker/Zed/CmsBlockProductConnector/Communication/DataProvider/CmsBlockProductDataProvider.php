<?php

namespace Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;
use Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\ProductAbstractQueryContainerInterface;

class CmsBlockProductDataProvider
{

    /**
     * @var CmsBlockProductConnectorQueryContainerInterface
     */
    protected $cmsBlockProductQueryContainer;

    /**
     * @var ProductAbstractQueryContainerInterface
     */
    protected $productAbstractQueryContainer;

    /**
     * @param CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductQueryContainer
     * @param ProductAbstractQueryContainerInterface $productAbstractQueryContainer
     */
    public function __construct(
        CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductQueryContainer,
        ProductAbstractQueryContainerInterface $productAbstractQueryContainer
    ) {
        $this->cmsBlockProductQueryContainer = $cmsBlockProductQueryContainer;
        $this->productAbstractQueryContainer = $productAbstractQueryContainer;
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
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
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
        $productAbstracts = $this->productAbstractQueryContainer
            ->queryProductAbstract()
            ->find();

        $productAbstractArray = [];

        foreach ($productAbstracts as $spyProductAbstract) {
            $productAbstractArray[$spyProductAbstract->getIdProductAbstract()] = $spyProductAbstract->getSku();
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