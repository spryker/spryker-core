<?php

namespace SprykerTest\Zed\Category\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryBuilder;
use Generated\Shared\DataBuilder\NodeBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CategoryDataHelper extends Module
{

    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveCategory($seedData = [])
    {
        $node = $this->generateCategoryNodeTransfer();
        $categoryTransfer = $this->generateCategoryTransfer($seedData);
        $categoryTransfer->setCategoryNode($node);
        $categoryTransfer->setParentCategoryNode($node);

        $this->getCategoryFacade()->create($categoryTransfer);

        return $categoryTransfer;
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

}
