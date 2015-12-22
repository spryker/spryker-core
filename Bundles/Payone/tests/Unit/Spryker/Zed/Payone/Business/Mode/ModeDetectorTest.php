<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Payone\Business\Mode;

use Spryker\Zed\Payone\Business\Mode\ModeDetector;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;
use Spryker\Zed\Payone\PayoneConfig;

/**
 * @group ModeDetector
 */
class ModeDetectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @todo impl of mode detector not final!
     *
     * @return void
     */
    public function testModeDetection()
    {
        $modeDetector = new ModeDetector(new PayoneConfig());
        $detectedMode = $modeDetector->getMode();

        $this->assertEquals(ModeDetectorInterface::MODE_TEST, $detectedMode);
    }

}
