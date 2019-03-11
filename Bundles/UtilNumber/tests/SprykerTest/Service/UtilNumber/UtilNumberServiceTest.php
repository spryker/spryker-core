<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilNumber;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RounderTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilNumber
 * @group UtilNumberServiceTest
 * Add your own group annotations below this line
 */
class UtilNumberServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilCsv\UtilCsvServiceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Service\UtilNumber\UtilNumberServiceInterface
     */
    protected $service;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = $this->tester->getLocator()->utilNumber()->service();
    }

    /**
     * @return void
     */
    public function testRoundToUpFraction(): void
    {
        $rounderTransfer = (new RounderTransfer())
            ->setPrecision(2)
            ->setRoundMode(PHP_ROUND_HALF_UP)
            ->setValue(1.775);

        $result = $this->service->round($rounderTransfer);

        $this->assertEquals(1.78, $result);
    }

    /**
     * @return void
     */
    public function testRoundToDownFraction(): void
    {
        $rounderTransfer = (new RounderTransfer())
            ->setPrecision(2)
            ->setRoundMode(PHP_ROUND_HALF_DOWN)
            ->setValue(1.775);

        $result = $this->service->round($rounderTransfer);

        $this->assertEquals(1.77, $result);
    }

    /**
     * @return void
     */
    public function testRoundToInt(): void
    {
        $rounderTransfer = (new RounderTransfer())
            ->setPrecision(0)
            ->setRoundMode(PHP_ROUND_HALF_UP)
            ->setValue(1.5);

        $result = $this->service->roundToInt($rounderTransfer);

        $this->assertEquals(2, $result);
    }
}
