<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Touch\Business\TouchBusinessFactory;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Touch\TouchDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Touch
 * @group Business
 * @group Model
 * @group TouchRecordTest
 * Add your own group annotations below this line
 */
class TouchRecordTest extends Unit
{
    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Business\Model\TouchRecordInterface
     */
    protected $touchRecord;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $container = new Container();
        $dependencyProvider = new TouchDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $businessFactory = new TouchBusinessFactory();
        $businessFactory->setContainer($container);

        $this->touchQueryContainer = new TouchQueryContainer();
        $this->touchRecord = $businessFactory->createTouchRecordModel();
    }

    /**
     * @return void
     */
    public function testSaveTouchRecordKeepsOneRecordIfKeyChangeFalse(): void
    {
        $this->touchRecord->saveTouchRecord('category', 'active', 1, false);
        $this->touchRecord->saveTouchRecord('category', 'deleted', 1, false);

        $this->assertCount(1, $this->touchQueryContainer->queryUpdateTouchEntry('category', 1));
    }
}
