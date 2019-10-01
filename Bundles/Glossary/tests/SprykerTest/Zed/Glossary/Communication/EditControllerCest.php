<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Communication\Controller;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use SprykerTest\Zed\Glossary\GlossaryCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Communication
 * @group Controller
 * @group EditControllerCest
 * Add your own group annotations below this line
 */
class EditControllerCest
{
    /**
     * @param \SprykerTest\Zed\Glossary\GlossaryCommunicationTester $i
     *
     * @return void
     */
    public function editTranslation(GlossaryCommunicationTester $i)
    {
        $formData = [
            KeyTranslationTransfer::GLOSSARY_KEY => 'test.translation.key',
            KeyTranslationTransfer::LOCALES => ['en_US' => 'en_US translation', 'de_DE' => 'de_DE translation'],
        ];

        $identifier = $i->haveTranslation($formData);

        $i->amOnPage('/glossary/edit?fk-glossary-key=' . $identifier);

        $formData['locales'] = ['en_US' => null, 'de_DE' => null];

        $i->submitForm(['name' => 'translation'], $formData);

        $i->seeResponseCodeIs(302);
        $i->amOnPage('/glossary');
    }
}
