<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace ZedUnit\SprykerFeature\Zed\Heartbeat\Business\Check;

use SprykerFeature\Zed\Heartbeat\Business\Check\Doctor;

class HeartbeatTest extends \PHPUnit_Framework_TestCase
{

    public function testCheckMustReturnTrueIfNoHeartbeatCheckerIsApplied()
    {
        $heartbeat = new Doctor([]);

        $this->assertTrue($heartbeat->check());
    }

    public function testCheckMustReturnTrueIfAllHeartbeatCheckerReturnTrue()
    {
        $checkerMock = $this->getMock('SprykerFeature\Shared\Heartbeat\Business\Check\HeartbeatCheckInterface', ['check']);
        $checkerMock->expects($this->once())
            ->method('check')
            ->will($this->returnValue(true))
        ;

        $heartbeat = new Doctor([$checkerMock]);

        $this->assertTrue($heartbeat->check());
    }

    public function testCheckMustReturnFalseIfAllHeartbeatCheckerReturnTrue()
    {
        $checkerMock = $this->getMock('SprykerFeature\Shared\Heartbeat\Business\Check\HeartbeatCheckInterface', ['check']);
        $checkerMock->expects($this->once())
            ->method('check')
            ->will($this->returnValue(false))
        ;

        $heartbeat = new Doctor([$checkerMock]);

        $this->assertFalse($heartbeat->check());
    }

    public function testCheckMustReturnFalseIfOneHeartbeatCheckerReturnFalse()
    {
        $checkerMockTrue = $this->getMock('SprykerFeature\Shared\Heartbeat\Business\Check\HeartbeatCheckInterface', ['check']);
        $checkerMockTrue->expects($this->once())
            ->method('check')
            ->will($this->returnValue(true))
        ;

        $checkerMockFalse = $this->getMock('SprykerFeature\Shared\Heartbeat\Business\Check\HeartbeatCheckInterface', ['check']);
        $checkerMockFalse->expects($this->once())
            ->method('check')
            ->will($this->returnValue(false))
        ;

        $heartbeat = new Doctor([$checkerMockFalse, $checkerMockTrue]);

        $this->assertFalse($heartbeat->check());
    }

}
