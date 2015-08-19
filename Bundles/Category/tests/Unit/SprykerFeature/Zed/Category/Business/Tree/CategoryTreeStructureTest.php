<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Category\Business\Tree;

use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeStructure;
use Unit\SprykerFeature\Zed\Category\Business\Tree\Fixtures\Expected\CategoryStructureExpected;
use Unit\SprykerFeature\Zed\Category\Business\Tree\Fixtures\Input\CategoryStructureInput;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Category
 * @group Business
 * @group CategoryTreeStructure
 */
class CategoryTreeStructureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var CategoryStructureInput
     */
    protected $input;

    /**
     * @var CategoryStructureExpected
     */
    protected $expected;

    public function setUp()
    {
        $this->factory = new Factory('Category');
        $this->input = new CategoryStructureInput();
        $this->expected = new CategoryStructureExpected();
    }

    public function testCategoryArrayTreeOrderedStructureIncreasingOrder()
    {
        $categories = $this->input->getOrderedCategoriesArray();

        $treeStructure = $this->factory
            ->createTreeCategoryTreeStructure($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getOrderedCategoriesArray(), $treeStructure);
    }

    public function testCategoryArrayTreeRandomOrder()
    {
        $categories = $this->input->getSecondOrderedCategoriesArray();

        $treeStructure = $this->factory
            ->createTreeCategoryTreeStructure($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getSecondOrderedCategoriesArray(), $treeStructure);
    }

    public function testUnorderedStructure()
    {
        $categories = $this->input->getCategoryStructureWithChildrenBeforeParent();

        $treeStructure = $this->factory
            ->createTreeCategoryTreeStructure($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getCategoryStructureWithChildrenBeforeParent(), $treeStructure);
    }

    public function testStructureWithParentNotExistant()
    {
        $categories = $this->input->getCategoryStructureWithNonexistantParent();

        $treeStructure = $this->factory
            ->createTreeCategoryTreeStructure($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getCategoryStructureWithNonexistantParent(), $treeStructure);
    }
}
