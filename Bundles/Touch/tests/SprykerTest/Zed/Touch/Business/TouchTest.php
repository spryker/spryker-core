<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Touch\Business\TouchBusinessFactory;
use Spryker\Zed\Touch\Business\TouchFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Touch\TouchDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Touch
 * @group Business
 * @group TouchTest
 * Add your own group annotations below this line
 */
class TouchTest extends Unit
{
    /**
     * @var \Spryker\Zed\Touch\Business\TouchFacade
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->touchFacade = new TouchFacade();
        $container = new Container();
        $dependencyProvider = new TouchDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $businessFactory = new TouchBusinessFactory();
        $businessFactory->setContainer($container);
        $this->touchFacade->setFactory($businessFactory);
        $this->touchQueryContainer = new TouchQueryContainer();
    }

    /**
     * @return void
     */
    public function testTouchActiveInsertsSomething(): void
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
    public function testTouchInactiveInsertsSomething(): void
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
    public function testTouchDeletedInsertsSomething(): void
    {
        $touchEntityQuery = $this->touchQueryContainer->queryTouchListByItemType('ProductTranslationWhatever');

        $touchCountBeforeTouch = $touchEntityQuery->count();
        $this->touchFacade->touchDeleted('ProductTranslationWhatever', 3);
        $touchCountAfterTouch = $touchEntityQuery->count();

        $this->assertTrue($touchCountAfterTouch > $touchCountBeforeTouch);
    }

    /**
     * @return void
     */
    public function testDeleteDeleted(): void
    {
        $this->touchFacade->touchDeleted('ProductTranslationWhatever', 3);
        $number = $this->touchFacade->removeTouchEntriesMarkedAsDeleted();
        $this->assertGreaterThan(0, $number);
    }
}
