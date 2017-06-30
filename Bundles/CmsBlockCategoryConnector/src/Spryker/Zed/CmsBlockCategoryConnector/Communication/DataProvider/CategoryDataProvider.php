<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;


use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CategoryType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CategoryDataProvider
{

    /**
     * @var CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @param CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     * @param CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer,
        CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
    ) {
        $this->queryContainer = $cmsBlockCategoryConnectorQueryContainer;
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
    }

    public function getOptions()
    {
        return [
            'data_class' => CategoryTransfer::class,
            CategoryType::OPTION_CMS_BLOCK_LIST => $this->getCmsBlockList(),
        ];
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return CategoryTransfer
     */
    public function getData(CategoryTransfer $categoryTransfer)
    {
        $idCmsBlocks = [];

        if ($categoryTransfer->getIdCategory()) {
            $idCmsBlocks = $this->getAssignedIdCmsBlocks($categoryTransfer->getIdCategory());
        }

        $categoryTransfer->setIdCmsBlocks($idCmsBlocks);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    protected function getAssignedIdCmsBlocks($idCategory)
    {
        return $this->queryContainer
            ->queryCmsBlockCategoryWithBlocksByIdCategory($idCategory)
            ->find()
            ->getColumnValues('fkCmsBlock');
    }

    /**
     * @return array
     */
    protected function getCmsBlockList()
    {
        return $this->cmsBlockQueryContainer
            ->queryCmsBlockWithTemplate()
            ->find()
            ->toKeyValue('idCmsBlock', 'name');
    }

}