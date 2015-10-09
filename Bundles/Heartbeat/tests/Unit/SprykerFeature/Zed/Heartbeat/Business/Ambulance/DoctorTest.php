<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Heartbeat\Business\Ambulance;

use Generated\Shared\Transfer\HealthIndicatorReportTransfer;
use Generated\Shared\Transfer\HealthReportTransfer;
use SprykerFeature\Zed\Heartbeat\Business\Ambulance\Doctor;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Heartbeat
 * @group Business
 * @group Doctor
 */
class DoctorTest extends \PHPUnit_Framework_TestCase
{

    public function testIsPatientAliveMustReturnTrueIfNoHealthIndicatorIsApplied()
    {
        $doctor = new Doctor([]);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    public function testIsPatientAliveMustReturnTrueIfAllHealthIndicatorReturnTrue()
    {
        $checkerMock = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMock->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(true)))
        ;

        $doctor = new Doctor([$checkerMock]);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    public function testIsPatientAliveMustReturnFalseIfAllHealthIndicatorReturnFalse()
    {
        $checkerMock = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMock->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(false)))
        ;

        $doctor = new Doctor([$checkerMock]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }

    public function testIsPatientAliveMustReturnFalseIfNotAllHealthIndicatorReturnTrue()
    {
        $checkerMockTrue = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMockTrue->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(false)))
        ;

        $checkerMockFalse = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['doHealthCheck']);
        $checkerMockFalse->expects($this->once())
            ->method('doHealthCheck')
            ->will($this->returnValue(new HealthIndicatorReportTransfer()))
        ;

        $doctor = new Doctor([$checkerMockFalse, $checkerMockTrue]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }

}
