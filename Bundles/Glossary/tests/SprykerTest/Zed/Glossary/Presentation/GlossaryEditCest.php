<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Presentation;

use SprykerTest\Zed\Glossary\PageObject\GlossaryListPage;
use SprykerTest\Zed\Glossary\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Presentation
 * @group GlossaryEditCest
 * Add your own group annotations below this line
 */
class GlossaryEditCest
{

    /**
     * @param \SprykerTest\Zed\Glossary\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(GlossaryListPage::URL);
        $i->wait(2);
        $i->click('(//a[contains(., "Edit")])[1]');

        $i->seeBreadcrumbNavigation('Dashboard / Glossary / Edit Glossary Translation');
    }

}
