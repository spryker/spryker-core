<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedCommunication\Controller;

use Sales\ZedCommunicationTester;

/**
 * @group IndexControllerCest
 */
class IndexControllerCest
{

    /**
     * @var \Sales\ZedCommunicationTester
     */
    protected $tester;

    /**
     * @param \Sales\ZedCommunicationTester $i
     *
     * @return void
     */
    public function testThatICanOpenSalesIndexPage(ZedCommunicationTester $i)
    {
        $i->amOnPage('/sales');
        $i->seeResponseCodeIs(200);
    }

    /**
     * @param \Sales\ZedCommunicationTester $i
     *
     * @return void
     */
    public function testThatICanOpenSalesIndexPageAgain(ZedCommunicationTester $i)
    {
        $i->amOnPage('/sales');
        $i->seeResponseCodeIs(200);
    }

}
