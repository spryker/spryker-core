<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Tree;

use Codeception\Test\Unit;
use Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter;
use SprykerTest\Zed\Category\Business\Tree\Fixtures\Expected\CategoryStructureExpected;
use SprykerTest\Zed\Category\Business\Tree\Fixtures\Input\CategoryStructureInput;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Tree
 * @group CategoryTreeStructureTest
 * Add your own group annotations below this line
 */
class CategoryTreeStructureTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Category\Business\Tree\Fixtures\Input\CategoryStructureInput
     */
    protected $input;

    /**
     * @var \SprykerTest\Zed\Category\Business\Tree\Fixtures\Expected\CategoryStructureExpected
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
     * @return void
     */
    public function testOutputTreeStructureFromRandomOrderCategoryArray()
    {
        $categories = $this->input->getCategoryStructureWithChildrenBeforeParent();

        $treeStructure = (new CategoryTreeFormatter($categories))
            ->getCategoryTree();

        $this->assertSame($this->expected->getCategoryStructureWithChildrenBeforeParent(), $treeStructure);
    }

    /**
     * @return void
     */
    public function testOutputStructureWithCategoryArrayItemThatParentDoesNotExist()
    {
        $categories = $this->input->getCategoryStructureWithNonexistentParent();

        $treeStructure = (new CategoryTreeFormatter($categories))
            ->getCategoryTree();

        $this->assertSame($this->expected->getCategoryStructureWithNonexistentParent(), $treeStructure);
    }
}
