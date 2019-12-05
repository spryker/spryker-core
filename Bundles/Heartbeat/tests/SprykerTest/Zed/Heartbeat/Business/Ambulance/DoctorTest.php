<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Heartbeat\Business\Ambulance;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\HealthIndicatorReportTransfer;
use Spryker\Zed\Heartbeat\Business\Ambulance\Doctor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Heartbeat
 * @group Business
 * @group Ambulance
 * @group DoctorTest
 * Add your own group annotations below this line
 */
class DoctorTest extends Unit
{
    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnTrueIfNoHealthIndicatorIsApplied(): void
    {
        $doctor = new Doctor([]);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnTrueIfAllHealthIndicatorReturnTrue(): void
    {
        $checkerMock = $this->getMockBuilder('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface')->setMethods(['doHealthCheck'])->getMock();
        $checkerMock->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(true)));

        $doctor = new Doctor([$checkerMock]);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnFalseIfAllHealthIndicatorReturnFalse(): void
    {
        $checkerMock = $this->getMockBuilder('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface')->setMethods(['doHealthCheck'])->getMock();
        $checkerMock->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(false)));

        $doctor = new Doctor([$checkerMock]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }

    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnFalseIfNotAllHealthIndicatorReturnTrue(): void
    {
        $checkerMockTrue = $this->getMockBuilder('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface')->setMethods(['doHealthCheck'])->getMock();
        $checkerMockTrue->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(false)));

        $checkerMockFalse = $this->getMockBuilder('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface')->setMethods(['doHealthCheck'])->getMock();
        $checkerMockFalse->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue(new HealthIndicatorReportTransfer()));

        $doctor = new Doctor([$checkerMockFalse, $checkerMockTrue]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }
}
