<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Communication\Controller;

use SprykerTest\Zed\Glossary\GlossaryCommunicationTester;
use SprykerTest\Zed\Glossary\PageObject\GlossaryListPage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Communication
 * @group Controller
 * @group GlossaryEditCest
 * Add your own group annotations below this line
 */
class GlossaryEditCest
{
    /**
     * @param \SprykerTest\Zed\Glossary\GlossaryCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(GlossaryCommunicationTester $i)
    {
        $i->listDataTable(GlossaryListPage::URL . '/index/table');
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Glossary / Edit Translation');
    }
}
