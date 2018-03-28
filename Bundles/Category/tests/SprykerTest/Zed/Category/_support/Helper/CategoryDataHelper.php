<?php

namespace SprykerTest\Zed\Category\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryBuilder;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\NodeBuilder;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\CategoryConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CategoryDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveCategory(array $seedData = [])
    {
        $seedData = $seedData + [
            'categoryNode' => $this->generateCategoryNodeTransfer(),
            'parentCategoryNode' => $this->generateCategoryNodeTransfer(),
        ];

        $categoryTransfer = $this->generateCategoryTransfer($seedData);

        $this->getCategoryFacade()->create($categoryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryTransfer) {
            $this->cleanupCategory($categoryTransfer);
        });

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveLocalizedCategory(array $seedData = [])
    {
        $seedData = $seedData + [
            'categoryNode' => $this->generateCategoryNodeTransfer(),
            'parentCategoryNode' => $this->generateCategoryNodeTransfer(),
        ];

        $categoryTransfer = $this->generateLocalizedCategoryTransfer($seedData);

        $this->getCategoryFacade()->create($categoryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryTransfer) {
            $this->cleanupCategory($categoryTransfer);
        });

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer|null
     */
    public function haveCategoryTemplate(array $seedData = [])
    {
        $this->getCategoryFacade()->syncCategoryTemplate();
        $categoryTemplateTransfer = $this->getCategoryFacade()
            ->findCategoryTemplateByName(CategoryConfig::CATEGORY_TEMPLATE_DEFAULT);

        $categoryTemplateTransfer->fromArray($seedData, true);

        return $categoryTemplateTransfer;
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function getCategoryFacade()
    {
        return $this->getLocator()->category()->facade();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function generateCategoryTransfer(array $seedData = [])
    {
        $categoryTransfer = (new CategoryBuilder($seedData))->build();
        $categoryTransfer->setIdCategory(null);

        if (!isset($seedData[CategoryTransfer::FK_CATEGORY_TEMPLATE])) {
            $categoryTemplateTransfer = $this->haveCategoryTemplate();
            $categoryTransfer->setFkCategoryTemplate($categoryTemplateTransfer->getIdCategoryTemplate());
        }

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function generateLocalizedCategoryTransfer(array $seedData = [])
    {
        $categoryTransfer = $this->generateCategoryTransfer($seedData);
        $categoryLocalizedAttributes = (new CategoryLocalizedAttributesBuilder($seedData))->build();
        $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributes);

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function generateCategoryNodeTransfer(array $seedData = [])
    {
        $categoryNodeTransfer = (new NodeBuilder($seedData))->build();

        return $categoryNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function cleanupCategory(CategoryTransfer $categoryTransfer)
    {
        $this->getCategoryFacade()->delete($categoryTransfer->getIdCategory());
    }
}
