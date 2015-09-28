<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Touch;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Touch\Business\TouchFacade;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerEngine\Zed\Touch\TouchDependencyProvider;

/**
 * @group Zed
 * @group Touch
 * @group Business
 * @group TouchTest
 */
class TouchTest extends AbstractFunctionalTest
{

    /**
     * @var TouchFacade
     */
    protected $touchFacade;

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    protected function setUp()
    {
        parent::setUp();
        $locator = Locator::getInstance();
        $this->touchFacade = $this->getFacade('SprykerEngine', 'Touch');
        $this->touchQueryContainer = new TouchQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Touch'), $locator);
    }

    public function testTouchActiveInsertsSomething()
    {
        $touchEntityQuery = $this->touchQueryContainer->queryTouchListByItemType('ProductTranslationWhatever');

        $touchCountBeforeTouch = $touchEntityQuery->count();
        $this->touchFacade->touchActive('ProductTranslationWhatever', 1);
        $touchCountAfterTouch = $touchEntityQuery->count();

        $this->assertTrue($touchCountAfterTouch > $touchCountBeforeTouch);
    }

    public function testTouchInactiveInsertsSomething()
    {
        $touchEntityQuery = $this->touchQueryContainer->queryTouchListByItemType('ProductTranslationWhatever');

        $touchCountBeforeTouch = $touchEntityQuery->count();
        $this->touchFacade->touchInactive('ProductTranslationWhatever', 2);
        $touchCountAfterTouch = $touchEntityQuery->count();

        $this->assertTrue($touchCountAfterTouch > $touchCountBeforeTouch);
    }

    public function testTouchDeletedInsertsSomething()
    {
        $touchEntityQuery = $this->touchQueryContainer->queryTouchListByItemType('ProductTranslationWhatever');

        $touchCountBeforeTouch = $touchEntityQuery->count();
        $this->touchFacade->touchDeleted('ProductTranslationWhatever', 3);
        $touchCountAfterTouch = $touchEntityQuery->count();

        $this->assertTrue($touchCountAfterTouch > $touchCountBeforeTouch);
    }

}
