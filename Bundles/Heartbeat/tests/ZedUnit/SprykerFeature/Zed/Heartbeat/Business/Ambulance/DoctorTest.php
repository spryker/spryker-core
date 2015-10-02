<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace ZedUnit\SprykerFeature\Zed\Heartbeat\Business\Ambulance;

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
        $doctor = new Doctor(new HealthReportTransfer(), []);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    public function testIsPatientAliveMustReturnTrueIfAllHealthIndicatorReturnTrue()
    {
        $checkerMock = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['check', 'getReport']);
        $checkerMock->expects($this->once())
            ->method('check')
            ->will($this->returnValue(true))
        ;
        $checkerMock->expects($this->once())
            ->method('getReport')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(true)))
        ;

        $doctor = new Doctor(new HealthReportTransfer(), [$checkerMock]);

        $this->assertTrue($doctor->doHealthCheck()->isPatientAlive());
    }

    public function testIsPatientAliveMustReturnFalseIfAllHealthIndicatorReturnTrue()
    {
        $checkerMock = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['check', 'getReport']);
        $checkerMock->expects($this->once())
            ->method('check')
            ->will($this->returnValue(false))
        ;
        $checkerMock->expects($this->once())
            ->method('getReport')
            ->will($this->returnValue(new HealthIndicatorReportTransfer()))
        ;

        $doctor = new Doctor(new HealthReportTransfer(), [$checkerMock]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }

    public function testIsPatientAliveMustReturnFalseIfNotAllHealthIndicatorReturnTrue()
    {
        $checkerMockTrue = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['check', 'getReport']);
        $checkerMockTrue->expects($this->once())
            ->method('check')
            ->will($this->returnValue(true))
        ;
        $checkerMockTrue->expects($this->once())
            ->method('getReport')
            ->will($this->returnValue((new HealthIndicatorReportTransfer())->setStatus(false)))
        ;

        $checkerMockFalse = $this->getMock('SprykerFeature\Shared\Heartbeat\Code\HealthIndicatorInterface', ['check', 'getReport']);
        $checkerMockFalse->expects($this->once())
            ->method('check')
            ->will($this->returnValue(false))
        ;
        $checkerMockFalse->expects($this->once())
            ->method('getReport')
            ->will($this->returnValue(new HealthIndicatorReportTransfer()))
        ;

        $doctor = new Doctor(new HealthReportTransfer(), [$checkerMockFalse, $checkerMockTrue]);

        $this->assertFalse($doctor->doHealthCheck()->isPatientAlive());
    }

}
