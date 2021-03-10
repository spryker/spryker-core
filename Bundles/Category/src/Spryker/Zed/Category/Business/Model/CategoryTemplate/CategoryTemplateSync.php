<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryTemplate;

use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryTemplateSync implements CategoryTemplateSyncInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\Category\CategoryConfig
     */
    protected $categoryConfig;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\Category\CategoryConfig $categoryConfig
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
    public function syncFromConfig(): void
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
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplate
     */
    protected function createCategoryTemplate()
    {
        return new SpyCategoryTemplate();
    }
}
