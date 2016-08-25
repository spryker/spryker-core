<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Heartbeat\Business\Ambulance;

use Generated\Shared\Transfer\HealthIndicatorReportTransfer;
use Spryker\Zed\Heartbeat\Business\Ambulance\Doctor;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Heartbeat
 * @group Business
 * @group Ambulance
 * @group DoctorTest
 */
class DoctorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnTrueIfNoHealthIndicatorIsApplied()
    {
        $doctor = new Doctor([]);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnTrueIfAllHealthIndicatorReturnTrue()
    {
        $checkerMock = $this->getMock('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMock->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(true)));

        $doctor = new Doctor([$checkerMock]);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnFalseIfAllHealthIndicatorReturnFalse()
    {
        $checkerMock = $this->getMock('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMock->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(false)));

        $doctor = new Doctor([$checkerMock]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }

    /**
     * @return void
     */
    public function testIsPatientAliveMustReturnFalseIfNotAllHealthIndicatorReturnTrue()
    {
        $checkerMockTrue = $this->getMock('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMockTrue->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(false)));

        $checkerMockFalse = $this->getMock('Spryker\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMockFalse->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue(new HealthIndicatorReportTransfer()));

        $doctor = new Doctor([$checkerMockFalse, $checkerMockTrue]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }

}
