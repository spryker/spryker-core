<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;


use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CategoryType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface;
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
     * @var CmsBlockCategoryConnectorToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var bool
     */
    protected $isTemplateSupported = true;

    /**
     * @param CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     * @param CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer,
        CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockCategoryConnectorToCategoryQueryContainerInterface $categoryQueryContainer
    ) {
        $this->queryContainer = $cmsBlockCategoryConnectorQueryContainer;
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    public function getOptions()
    {
        return [
            'data_class' => CategoryTransfer::class,
            CategoryType::OPTION_CMS_BLOCK_LIST => $this->getCmsBlockList(),
            CategoryType::OPTION_CMS_BLOCK_POSITION_LIST => $this->getPositionList(),
            CategoryType::OPTION_IS_TEMPLATE_SUPPORTED => $this->isTemplateSupported()
        ];
    }

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return CategoryTransfer
     */
    public function getData(CategoryTransfer $categoryTransfer)
    {
        $this->assertTemplate($categoryTransfer);

        $idCmsBlocks = [];
        if ($categoryTransfer->getIdCategory()) {
            $idCmsBlocks = $this->getAssignedIdCmsBlocks(
                $categoryTransfer->getIdCategory(),
                $categoryTransfer->getFkCategoryTemplate()
            );
        }

        $categoryTransfer->setIdCmsBlocks($idCmsBlocks);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return array
     */
    protected function getAssignedIdCmsBlocks($idCategory, $idCategoryTemplate)
    {
        $query = $this->queryContainer
            ->queryCmsBlockCategoryWithBlocksByIdCategory($idCategory, $idCategoryTemplate)
            ->find();

        $assignedBlocks = [];

        foreach ($query as $item) {
            $assignedBlocks[$item->getFkCmsBlockCategoryPosition()][] = $item->getFkCmsBlock();
        }

        return $assignedBlocks;
    }

    /**
     * @return array
     */
    protected function getPositionList()
    {
        return $this->queryContainer
            ->queryCmsBlockCategoryPosition()
            ->orderByIdCmsBlockCategoryPosition()
            ->find()
            ->toKeyValue('idCmsBlockCategoryPosition', 'name');
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

    /**
     * @param CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function assertTemplate(CategoryTransfer $categoryTransfer)
    {
        $spyCategoryTemplate = $this->categoryQueryContainer
            ->queryCategoryTemplateById($categoryTransfer->getFkCategoryTemplate())
            ->findOne();

        if (!in_array($spyCategoryTemplate->getName(), CategoryType::SUPPORTED_CATEGORY_TEMPLATE_LIST)) {
            $this->isTemplateSupported = false;
        }
    }

    /**
     * @return bool
     */
    protected function isTemplateSupported()
    {
        return $this->isTemplateSupported;
    }

}