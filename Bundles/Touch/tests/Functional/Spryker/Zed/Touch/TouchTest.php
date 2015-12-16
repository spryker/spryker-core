<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Touch;

use Spryker\Zed\Kernel\AbstractFunctionalTest;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Touch\Business\TouchDependencyContainer;
use Spryker\Zed\Touch\Business\TouchFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Spryker\Zed\Touch\TouchDependencyProvider;

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
        $this->touchFacade->setBusinessFactory($dependencyContainer);
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
