<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use SprykerTest\Zed\Oms\OmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group GetOmsOrderItemStateTest
 * Add your own group annotations below this line
 */
class GetOmsOrderItemStateTest extends Unit
{
    /**
     * @var string
     */
    protected const NEW_STATE_NAME = 'new';

    /**
     * @var string
     */
    protected const DUMMY_STATE_NAME = 'dummy';

    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected OmsBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureOmsOrderItemStateDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldRetrieveOmsOrderItemState(): void
    {
        // Arrange
        $persistedOmsOrderItemState = $this->tester->haveOmsOrderItemState([
            OmsOrderItemStateTransfer::NAME => static::NEW_STATE_NAME,
            OmsOrderItemStateTransfer::DESCRIPTION => 'description',
        ]);

        // Act
        $omsOrderItemStateTransfer = $this->tester->getFacade()->getOmsOrderItemState(static::NEW_STATE_NAME);

        // Assert
        $this->assertSame($persistedOmsOrderItemState->getIdOmsOrderItemState(), $omsOrderItemStateTransfer->getIdOmsOrderItemState());
        $this->assertSame($persistedOmsOrderItemState->getName(), $omsOrderItemStateTransfer->getName());
        $this->assertSame($persistedOmsOrderItemState->getDescription(), $omsOrderItemStateTransfer->getDescription());
    }

    /**
     * @return void
     */
    public function testShouldCreateOmsOrderItemState(): void
    {
        // Act
        $omsOrderItemStateTransfer = $this->tester->getFacade()->getOmsOrderItemState(static::DUMMY_STATE_NAME);

        // Assert
        $this->assertSame(static::DUMMY_STATE_NAME, $omsOrderItemStateTransfer->getName());
    }
}
