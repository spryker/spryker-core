<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Communication\Controller;

use SprykerTest\Zed\Glossary\GlossaryCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Communication
 * @group Controller
 * @group IndexControllerCest
 * Add your own group annotations below this line
 */
class IndexControllerCest
{
    /**
     * @param \SprykerTest\Zed\Glossary\GlossaryCommunicationTester $i
     *
     * @return void
     */
    public function listTranslations(GlossaryCommunicationTester $i)
    {
        $i->amOnPage('/glossary');
        $i->seeResponseCodeIs(200);
        $i->see('Translations', 'h5');
    }
}
