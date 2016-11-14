<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\UtilSanitize;

use Codeception\TestCase\Test;
use Spryker\Service\UtilSanitize\UtilSanitizeService;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group UtilSanitize
 * @group UtilSanitizeServiceTest
 */

class UtilSanitizeServiceTest extends Test
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
