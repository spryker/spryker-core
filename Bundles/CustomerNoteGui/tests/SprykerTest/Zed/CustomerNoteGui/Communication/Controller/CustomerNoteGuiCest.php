<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerNoteGui\Communication\Controller;

use SprykerTest\Zed\CustomerNoteGui\CustomerNoteGuiCommunicationTester;
use SprykerTest\Zed\CustomerNoteGui\PageObject\CustomerNotePage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerNoteGui
 * @group Communication
 * @group Controller
 * @group CustomerNoteGuiCest
 * Add your own group annotations below this line
 */
class CustomerNoteGuiCest
{
    /**
     * @param \SprykerTest\Zed\CustomerNoteGui\CustomerNoteGuiCommunicationTester $i
     *
     * @return void
     */
    public function testIndexAction(CustomerNoteGuiCommunicationTester $i)
    {
        $i->sendAjaxPostRequest(CustomerNotePage::URL, [
            'customerTransfer' => $this->getCustomer($i),
        ]);
        $i->canSeeResponseCodeIs(200);
    }

    /**
     * @param \SprykerTest\Zed\CustomerNoteGui\CustomerNoteGuiCommunicationTester $i
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(CustomerNoteGuiCommunicationTester $i)
    {
        return $i->haveCustomer();
    }
}
