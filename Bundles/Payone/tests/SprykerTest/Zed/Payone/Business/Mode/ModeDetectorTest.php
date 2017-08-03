<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payone\Business\Mode;

use Codeception\Test\Unit;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;
use Spryker\Zed\Payone\Business\Mode\ModeDetector;
use Spryker\Zed\Payone\PayoneConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payone
 * @group Business
 * @group Mode
 * @group ModeDetectorTest
 * Add your own group annotations below this line
 */
class ModeDetectorTest extends Unit
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
