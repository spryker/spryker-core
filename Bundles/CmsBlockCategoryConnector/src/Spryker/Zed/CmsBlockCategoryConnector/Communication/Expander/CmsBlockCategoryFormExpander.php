<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Expander;

use Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider\CategoryDataProvider;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CmsBlockCategoryFormExpander implements CmsBlockCategoryFormExpanderInterface
{
    /**
     * @var \Spryker\Zed\Kernel\Communication\Form\AbstractType
     */
    protected $categoryType;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider\CategoryDataProvider
     */
    protected $categoryDataProvider;

    /**
     * @param \Spryker\Zed\Kernel\Communication\Form\AbstractType $categoryType
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider\CategoryDataProvider $categoryDataProvider
     */
    public function __construct(
        AbstractType $categoryType,
        CategoryDataProvider $categoryDataProvider
    ) {
        $this->categoryType = $categoryType;
        $this->categoryDataProvider = $categoryDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $categoryTransfer = $builder->getData();
        $this->categoryDataProvider->getData($categoryTransfer);

        $this->categoryType->buildForm(
            $builder,
            $this->categoryDataProvider->getOptions()
        );
    }
}
