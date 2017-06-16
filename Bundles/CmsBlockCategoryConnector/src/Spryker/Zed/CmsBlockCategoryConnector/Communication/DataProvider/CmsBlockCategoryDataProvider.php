<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockCategoryType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\LocaleFacadeInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CategoryQueryContainerInterface;

class CmsBlockCategoryDataProvider
{

    /**
     * @var CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     * @param LocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        LocaleFacadeInterface $localeFacade

    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeFacade = $localeFacade;
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
     * @param int|null $idCmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function getData($idCmsBlock = null)
    {
        if (!$idCmsBlock) {
            $cmsBlockTransfer = new CmsBlockTransfer();
        } else {
//            $cmsBlockTransfer = $this->cmsBlockFacade->findCmsBlockId($idCmsBlock);
        }

        $cmsBlockTransfer = new CmsBlockTransfer();
        return $cmsBlockTransfer;
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