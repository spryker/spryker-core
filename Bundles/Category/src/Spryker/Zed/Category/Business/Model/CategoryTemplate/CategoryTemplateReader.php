<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryTemplate;

use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryTemplateReader implements CategoryTemplateReaderInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct(CategoryQueryContainerInterface $categoryQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer|null
     */
    public function findCategoryTemplateByName($name)
    {
        $spyCategoryTemplate = $this->categoryQueryContainer
            ->queryCategoryTemplateByName($name)
            ->findOne();

        if (!$spyCategoryTemplate) {
            return null;
        }

        $categoryTemplateTransfer = $this->createCategoryTemplateTransfer();
        $categoryTemplateTransfer = $this->mapEntityToTransfer($spyCategoryTemplate, $categoryTemplateTransfer);

        return $categoryTemplateTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryTemplate $spyCategoryTemplate
     * @param \Generated\Shared\Transfer\CategoryTemplateTransfer $categoryTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer
     */
    protected function mapEntityToTransfer(SpyCategoryTemplate $spyCategoryTemplate, CategoryTemplateTransfer $categoryTemplateTransfer)
    {
        $categoryTemplateTransfer->fromArray($spyCategoryTemplate->toArray(), true);

        return $categoryTemplateTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer
     */
    protected function createCategoryTemplateTransfer()
    {
        return new CategoryTemplateTransfer();
    }

}
