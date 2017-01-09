<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Category\Business\Tree;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;
use Unit\Spryker\Zed\Category\Business\Tree\Fixtures\Expected\CategoryStructureExpected;
use Unit\Spryker\Zed\Category\Business\Tree\Fixtures\Input\CategoryStructureInput;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Category
 * @group Business
 * @group Tree
 * @group CategoryTreeStructureTest
 */
class CategoryTreeStructureTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Unit\Spryker\Zed\Category\Business\Tree\Fixtures\Input\CategoryStructureInput
     */
    protected $input;

    /**
     * @var \Unit\Spryker\Zed\Category\Business\Tree\Fixtures\Expected\CategoryStructureExpected
     */
    protected $expected;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->input = new CategoryStructureInput();
        $this->expected = new CategoryStructureExpected();
    }

    /**
     * @todo find better names for test methods
     *
     * @return void
     */
    public function testOutputTreeStructureFromOrderedCategoriesArray()
    {
        $categories = $this->input->getOrderedCategoriesArray();

        $treeStructure = (new CategoryTreeFormatter($categories))
            ->getCategoryTree();

        $this->assertSame($this->expected->getOrderedCategoriesArray(), $treeStructure);
    }

    /**
     * @todo find better names for test methods
     *
     * @return void
     */
    public function testOutputTreeStructureFromOrderedCategoriesArrayWhereParentWasChangedForAnItem()
    {
        $categories = $this->input->getSecondOrderedCategoriesArray();

        $treeStructure = (new CategoryTreeFormatter($categories))
            ->getCategoryTree();

        $this->assertSame($this->expected->getSecondOrderedCategoriesArray(), $treeStructure);
    }

    /**
     * @todo find better names for test methods
     *
     * @return void
     */
    public function testOuputTreeStructureFromRandomOrderCategoryArray()
    {
        $categories = $this->input->getCategoryStructureWithChildrenBeforeParent();

        $treeStructure = (new CategoryTreeFormatter($categories))
            ->getCategoryTree();

        $this->assertSame($this->expected->getCategoryStructureWithChildrenBeforeParent(), $treeStructure);
    }

    /**
     * @todo find better names for test methods
     *
     * @return void
     */
    public function testOutputStructureWithCategoryArrayItemThatParentDoesNotExist()
    {
        $categories = $this->input->getCategoryStructureWithNonexistantParent();

        $treeStructure = (new CategoryTreeFormatter($categories))
            ->getCategoryTree();

        $this->assertSame($this->expected->getCategoryStructureWithNonexistantParent(), $treeStructure);
    }

}
