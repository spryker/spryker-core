<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockCategoryType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\LocaleFacadeInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CategoryQueryContainerInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockCategoryDataProvider
{

    /**
     * @var CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $cmsBlockCategoryConnectorQueryContainer;

    /**
     * @var CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     * @param LocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer,
        CategoryQueryContainerInterface $categoryQueryContainer,
        LocaleFacadeInterface $localeFacade
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->cmsBlockCategoryConnectorQueryContainer = $cmsBlockCategoryConnectorQueryContainer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsBlockTransfer::class,
            CmsBlockCategoryType::OPTION_CATEGORIES => $this->getCategoryList(),
        ];
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function getData(CmsBlockTransfer $cmsBlockTransfer)
    {
        $categoryIds = [];

        if ($cmsBlockTransfer->getIdCmsBlock()) {
            $categoryIds = $this->getAssignedCategoryIds($cmsBlockTransfer->getIdCmsBlock());
        }

        $cmsBlockTransfer->setCategories($categoryIds);

        return $cmsBlockTransfer;
    }

    /**
     * @param int $idCmsBlock
     * @return array
     */
    protected function getAssignedCategoryIds($idCmsBlock)
    {
        return $this->cmsBlockCategoryConnectorQueryContainer
            ->queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock)
            ->find()
            ->getColumnValues('fkCategory');
    }

    /**
     * @return array
     */
    protected function getCategoryList()
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $categoryCollection = $this->categoryQueryContainer
            ->queryCategory($idLocale)
            ->find();

        $categoryList = [];

        /** @var SpyCategory $spyCategory */
        foreach ($categoryCollection->getData() as $spyCategory) {
            $categoryName = $spyCategory->getLocalisedAttributes($idLocale)->getFirst()->getName();
            $categoryList[$spyCategory->getIdCategory()] = $categoryName;
        }

        return $categoryList;
    }
}