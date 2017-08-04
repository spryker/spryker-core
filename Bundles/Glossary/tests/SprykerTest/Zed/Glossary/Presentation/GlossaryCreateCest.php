<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Presentation;

use SprykerTest\Zed\Glossary\GlossaryPresentationTester;
use SprykerTest\Zed\Glossary\PageObject\GlossaryCreatePage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Presentation
 * @group GlossaryCreateCest
 * Add your own group annotations below this line
 */
class GlossaryCreateCest
{

    /**
     * @param \SprykerTest\Zed\Glossary\GlossaryPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(GlossaryPresentationTester $i)
    {
        $i->amOnPage(GlossaryCreatePage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Glossary / Add Glossary Translation');
    }

}
