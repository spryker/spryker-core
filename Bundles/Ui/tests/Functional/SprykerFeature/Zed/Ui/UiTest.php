<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Ui;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group UiTest
 */
class UiTest extends Test
{

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
    }

    /**
     * @group Ui
     */
    public function testGetFormField()
    {
        $field = $this->locator->ui()->pluginFormField();

        $this->assertInstanceOf('SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field', $field);
    }

    /**
     * @group Ui
     */
    public function testGetGridDefaultRowsRenderer()
    {
        $field = $this->locator->ui()->pluginGridDefaultRowsRenderer();

        $this->assertInstanceOf('SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DefaultRowsRenderer', $field);
    }

    /**
     * @group Ui
     */
    public function testGetGridPagination()
    {
        $field = $this->locator->ui()->pluginGridPagination();

        $this->assertInstanceOf('SprykerFeature\Zed\Ui\Communication\Plugin\Grid\Pagination', $field);
    }

    /**
     * @group Ui
     */
    public function testGetGridDefaultColumn()
    {
        $field = $this->locator->ui()->pluginGridDefaultColumn();

        $this->assertInstanceOf('SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DefaultColumn', $field);
    }

    /**
     * @group Ui
     */
    public function testGetGridBooleanColumn()
    {
        $field = $this->locator->ui()->pluginGridBooleanColumn();

        $this->assertInstanceOf('SprykerFeature\Zed\Ui\Communication\Plugin\Grid\BooleanColumn', $field);
    }

    /**
     * @group Ui
     */
    public function testGetGridDateTimeColumn()
    {
        $field = $this->locator->ui()->pluginGridDateTimeColumn();

        $this->assertInstanceOf('SprykerFeature\Zed\Ui\Communication\Plugin\Grid\DateTimeColumn', $field);
    }

}
