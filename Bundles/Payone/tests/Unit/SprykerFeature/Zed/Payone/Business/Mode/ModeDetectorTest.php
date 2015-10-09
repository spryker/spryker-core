<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Payone\Business\Mode;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Zed\Payone\Business\Mode\ModeDetector;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerFeature\Zed\Payone\PayoneConfig;

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
        $modeDetector = new ModeDetector(new PayoneConfig(\SprykerEngine\Shared\Config::getInstance(), Locator::getInstance()));
        $detectedMode = $modeDetector->getMode();

        $this->assertEquals(ModeDetectorInterface::MODE_TEST, $detectedMode);
    }

}
