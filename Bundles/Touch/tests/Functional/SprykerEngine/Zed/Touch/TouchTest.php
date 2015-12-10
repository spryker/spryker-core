<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Touch;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Touch\Business\TouchDependencyContainer;
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

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->touchFacade = new TouchFacade();
        $container = new Container();
        $dependencyProvider = new TouchDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyContainer = new TouchDependencyContainer();
        $dependencyContainer->setContainer($container);
        $this->touchFacade->setDependencyContainer($dependencyContainer);
        $this->touchQueryContainer = new TouchQueryContainer();
    }

    /**
     * @return void
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
     * @return void
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
     * @return void
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
