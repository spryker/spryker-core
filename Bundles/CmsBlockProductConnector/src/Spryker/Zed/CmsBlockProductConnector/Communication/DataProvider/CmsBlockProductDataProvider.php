<?php

namespace Spryker\Zed\CmsBlockProductConnector\Communication\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockProductConnector\Dependency\Facade\LocaleFacadeInterface;
use Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface;
use Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\ProductAbstractQueryContainerInterface;

class CmsBlockProductDataProvider
{
    const PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME = 'name';

    /**
     * @var CmsBlockProductConnectorQueryContainerInterface
     */
    protected $cmsBlockProductQueryContainer;

    /**
     * @var ProductAbstractQueryContainerInterface
     */
    protected $productAbstractQueryContainer;

    /**
     * @var LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductQueryContainer
     * @param ProductAbstractQueryContainerInterface $productAbstractQueryContainer
     */
    public function __construct(
        CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductQueryContainer,
        ProductAbstractQueryContainerInterface $productAbstractQueryContainer,
        LocaleFacadeInterface $localeFacade
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
        $idLocale = $this->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();

        $productAbstracts = $this->productAbstractQueryContainer
            ->queryProductAbstractWithName($idLocale)
            ->find();

        $productAbstractArray = [];

        foreach ($productAbstracts as $spyProductAbstract) {
            $productAbstractArray[$spyProductAbstract->getIdProductAbstract()] =
                $spyProductAbstract->getVirtualColumn(static::PRODUCT_ABSTRACT_VIRTUAL_COLUMN_NAME);
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