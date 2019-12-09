<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\HealthCheck\Communication\Controller;

use SprykerTest\Zed\HealthCheck\HealthCheckCommunicationTester;
use SprykerTest\Zed\HealthCheck\PageObject\HealthCheckPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group HealthCheck
 * @group Communication
 * @group Controller
 * @group HealthCheckCest
 * Add your own group annotations below this line
 */
class HealthCheckCest
{
    /**
     * @param \SprykerTest\Zed\HealthCheck\HealthCheckCommunicationTester $i
     *
     * @return void
     */
    public function testForbiddenHealthCheckStatusResponse(HealthCheckCommunicationTester $i): void
    {
        $i->disableHealthCheckEndpoints();
        $i->amOnPage(HealthCheckPage::URL_INDEX);
        $i->seeResponseCodeIs(403);
    }

    /**
     * @param \SprykerTest\Zed\HealthCheck\HealthCheckCommunicationTester $i
     *
     * @return void
     */
    public function testSuccessHealthCheckStatusResponse(HealthCheckCommunicationTester $i): void
    {
        $i->enableHealthCheckEndpoints();
        $i->amOnPage(HealthCheckPage::URL_INDEX);
        $i->seeResponseCodeIs(200);
    }

    /**
     * @param \SprykerTest\Zed\HealthCheck\HealthCheckCommunicationTester $i
     *
     * @return void
     */
    public function testSuccessHealthCheckStatusResponseForWithFilter(HealthCheckCommunicationTester $i): void
    {
        $i->enableHealthCheckEndpoints();
        $i->amOnPage(HealthCheckPage::URL_INDEX_SERVICES);
        $i->seeResponseCodeIs(400);
    }
}
