<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Category\Business\Tree;

use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerFeature\Zed\Category\Business\Tree\CategoryTreeFormatter;
use Unit\SprykerFeature\Zed\Category\Business\Tree\Fixtures\Expected\CategoryStructureExpected;
use Unit\SprykerFeature\Zed\Category\Business\Tree\Fixtures\Input\CategoryStructureInput;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Category
 * @group Business
 * @group CategoryTreeFormatter
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

    /**
     * @todo find better names for test methods
     */
    public function testOutputTreeStructureFromOrderedCategoriesArray()
    {
        $categories = $this->input->getOrderedCategoriesArray();

        $treeStructure = $this->factory
            ->createTreeFormatterCategoryTreeFormatter($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getOrderedCategoriesArray(), $treeStructure);
    }

    /**
     * @todo find better names for test methods
     */
    public function testOutputTreeStructureFromOrderedCategoriesArrayWhereParentWasChangedForAnItem()
    {
        $categories = $this->input->getSecondOrderedCategoriesArray();

        $treeStructure = $this->factory
            ->createTreeFormatterCategoryTreeFormatter($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getSecondOrderedCategoriesArray(), $treeStructure);
    }

    /**
     * @todo find better names for test methods
     */
    public function testOuputTreeStructureFromRandomOrderCategoryArray()
    {
        $categories = $this->input->getCategoryStructureWithChildrenBeforeParent();

        $treeStructure = $this->factory
            ->createTreeFormatterCategoryTreeFormatter($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getCategoryStructureWithChildrenBeforeParent(), $treeStructure);
    }

    /**
     * @todo find better names for test methods
     */
    public function testOutputStructureWithCategoryArrayItemThatParentDoesNotExist()
    {
        $categories = $this->input->getCategoryStructureWithNonexistantParent();

        $treeStructure = $this->factory
            ->createTreeFormatterCategoryTreeFormatter($categories)
            ->getCategoryTree()
        ;

        $this->assertSame($this->expected->getCategoryStructureWithNonexistantParent(), $treeStructure);
    }

}
