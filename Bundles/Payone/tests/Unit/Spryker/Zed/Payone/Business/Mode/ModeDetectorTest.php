<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Payone\Business\Mode;

use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;
use Spryker\Zed\Payone\Business\Mode\ModeDetector;
use Spryker\Zed\Payone\PayoneConfig;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Payone
 * @group Business
 * @group Mode
 * @group ModeDetectorTest
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
