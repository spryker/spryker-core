<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Presentation;

use SprykerTest\Zed\Tax\PageObject\TaxSetCreatePage;
use SprykerTest\Zed\Tax\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Tax
 * @group Presentation
 * @group TaxSetCreateCest
 * Add your own group annotations below this line
 */
class TaxSetCreateCest
{

    /**
     * @param \SprykerTest\Zed\Tax\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(TaxSetCreatePage::URL);

        $i->seeBreadcrumbNavigation('Dashboard / Taxes / Tax Sets / Create New Tax Set');
    }

}
