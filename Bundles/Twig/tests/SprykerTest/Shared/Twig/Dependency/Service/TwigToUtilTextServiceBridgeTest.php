<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Dependency\Service;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceBridge;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group Dependency
 * @group Service
 * @group TwigToUtilTextServiceBridgeTest
 * Add your own group annotations below this line
 */
class TwigToUtilTextServiceBridgeTest extends Unit
{
    public const STRING_TO_CONVERT = 'StringToConvert';
    public const EXPECTED_STRING = 'string-to-convert';

    /**
     * @return void
     */
    public function testCamelCaseToDashReturnConvertedString()
    {
        $utilTextService = new UtilTextService();
        $twigToUtilTextBridge = new TwigToUtilTextServiceBridge($utilTextService);

        $this->assertSame(static::EXPECTED_STRING, $twigToUtilTextBridge->camelCaseToDash(static::STRING_TO_CONVERT));
    }
}
