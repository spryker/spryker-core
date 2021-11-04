<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\DataProvider\Create;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface;
use Spryker\Zed\CategoryGui\Communication\Form\RootCategoryType;
use Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface;

class RootCategoryCreateDataProvider
{
    /**
     * @var \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    protected $categoryGuiRepository;

    /**
     * @var \Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface
     */
    protected $categoryExpander;

    /**
     * @param \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface $categoryGuiRepository
     * @param \Spryker\Zed\CategoryGui\Communication\Expander\CategoryExpanderInterface $categoryExpander
     */
    public function __construct(
        CategoryGuiRepositoryInterface $categoryGuiRepository,
        CategoryExpanderInterface $categoryExpander
    ) {
        $this->categoryGuiRepository = $categoryGuiRepository;
        $this->categoryExpander = $categoryExpander;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getData(): CategoryTransfer
    {
        $categoryTransfer = (new CategoryTransfer())
            ->setIsActive(false)
            ->setIsInMenu(true)
            ->setIsClickable(true)
            ->setIsSearchable(true)
            ->setStoreRelation(new StoreRelationTransfer());

        $categoryTransfer->setCategoryNode(
            (new NodeTransfer())
                ->setIsMain(true)
                ->setIsRoot(true),
        );

        return $this->categoryExpander->expandCategoryWithLocalizedAttributes($categoryTransfer);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            RootCategoryType::OPTION_DATA_CLASS => CategoryTransfer::class,
            RootCategoryType::OPTION_CATEGORY_TEMPLATE_CHOICES => $this->categoryGuiRepository->getIndexedCategoryTemplateNames(),
        ];
    }
}
