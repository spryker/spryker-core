<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilSanitize;

use Codeception\Test\Unit;
use Spryker\Service\UtilSanitize\UtilSanitizeService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilSanitize
 * @group UtilSanitizeServiceTest
 * Add your own group annotations below this line
 */
class UtilSanitizeServiceTest extends Unit
{
    /**
     * @return void
     */
    public function testSanitizeHtmlShouldEscapeGivenHtmlTags()
    {
        $utilSanitizeService = $this->createUtilSanitizeService();

        $escapedHtml = $utilSanitizeService->escapeHtml('<b></b>');

        $this->assertEquals('&lt;b&gt;&lt;/b&gt;', $escapedHtml);
    }

    /**
     * @return \Spryker\Service\UtilSanitize\UtilSanitizeService
     */
    protected function createUtilSanitizeService()
    {
        return new UtilSanitizeService();
    }
}
