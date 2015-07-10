<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Touch;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Touch\Business\TouchFacade;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;

class TouchTest extends Test
{

    /**
     * @var \SprykerEngine\Zed\Touch\Business\TouchFacade
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
        $this->touchFacade = new TouchFacade(new Factory('Touch'), $locator);

        $this->touchQueryContainer = new TouchQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Touch'), $locator);
    }

    /**
     * @group Touch
     */
    public function testTouchActiveInsertsSomething()
    {
        $touchEntityQuery = $this->touchQueryContainer->queryTouchListByItemType('ProductTranslationWhatever');

        $touchCountBeforeTouch = $touchEntityQuery->count();
        $this->touchFacade->touchActive('ProductTranslationWhatever', 1);
        $touchCountAfterTouch = $touchEntityQuery->count();

        $this->assertTrue($touchCountAfterTouch > $touchCountBeforeTouch);
    }

    /**
     * @group Touch
     */
    public function testTouchInactiveInsertsSomething()
    {
        $touchEntityQuery = $this->touchQueryContainer->queryTouchListByItemType('ProductTranslationWhatever');

        $touchCountBeforeTouch = $touchEntityQuery->count();
        $this->touchFacade->touchInactive('ProductTranslationWhatever', 2);
        $touchCountAfterTouch = $touchEntityQuery->count();

        $this->assertTrue($touchCountAfterTouch > $touchCountBeforeTouch);
    }

    /**
     * @group Touch
     */
    public function testTouchDeletedInsertsSomething()
    {
        $touchEntityQuery = $this->touchQueryContainer->queryTouchListByItemType('ProductTranslationWhatever');

        $touchCountBeforeTouch = $touchEntityQuery->count();
        $this->touchFacade->touchDeleted('ProductTranslationWhatever', 3);
        $touchCountAfterTouch = $touchEntityQuery->count();

        $this->assertTrue($touchCountAfterTouch > $touchCountBeforeTouch);
    }

}
