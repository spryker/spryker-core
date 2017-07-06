<?php

namespace Spryker\Zed\Category\Business\Model\CategoryTemplate;


use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryTemplateSync implements CategoryTemplateSyncInterface
{

    /**
     * @var CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var CategoryConfig
     */
    protected $categoryConfig;

    /**
     * @param CategoryQueryContainerInterface $categoryQueryContainer
     * @param CategoryConfig $categoryConfig
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        CategoryConfig $categoryConfig
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->categoryConfig = $categoryConfig;
    }

    /**
     * @return void
     */
    public function syncFromConfig()
    {
        $templateList = $this->categoryConfig->getTemplateList();

        foreach ($templateList as $templateName => $templatePath) {

            $spyCategoryTemplate = $this->categoryQueryContainer
                ->queryCategoryTemplateByName($templateName)
                ->findOne();

            if (!$spyCategoryTemplate) {
                $spyCategoryTemplate = $this->createCategoryTemplate();
                $spyCategoryTemplate->setName($templateName);
                $spyCategoryTemplate->setTemplatePath($templatePath);
                $spyCategoryTemplate->save();
            }
        }
    }

    /**
     * @return SpyCategoryTemplate
     */
    protected function createCategoryTemplate()
    {
        return new SpyCategoryTemplate();
    }
}