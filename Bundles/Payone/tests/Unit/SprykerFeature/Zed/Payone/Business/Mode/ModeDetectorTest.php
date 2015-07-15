<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Payone\Business\Mode;

use SprykerFeature\Zed\Payone\Business\Mode\ModeDetector;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;

/**
 * @group ModeDetector
 */
class ModeDetectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @todo impl of mode detector not final!
     */
    public function testModeDetection()
    {
        $modeDetector = new ModeDetector();
        $detectedMode = $modeDetector->getMode();

        $this->assertEquals(ModeDetectorInterface::MODE_TEST, $detectedMode);
    }

}
