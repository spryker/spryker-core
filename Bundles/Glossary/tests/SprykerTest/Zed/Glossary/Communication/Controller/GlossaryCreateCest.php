<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Communication\Controller;

use SprykerTest\Zed\Glossary\GlossaryCommunicationTester;
use SprykerTest\Zed\Glossary\PageObject\GlossaryCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Communication
 * @group Controller
 * @group GlossaryCreateCest
 * Add your own group annotations below this line
 */
class GlossaryCreateCest
{
    /**
     * @param \SprykerTest\Zed\Glossary\GlossaryCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(GlossaryCommunicationTester $i)
    {
        $i->amOnPage(GlossaryCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Glossary / Create Translation');
    }
}
