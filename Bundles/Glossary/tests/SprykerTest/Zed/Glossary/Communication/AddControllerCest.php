<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Communication\Controller;

use Generated\Shared\DataBuilder\KeyTranslationBuilder;
use SprykerTest\Zed\Glossary\GlossaryCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Communication
 * @group Controller
 * @group AddControllerCest
 * Add your own group annotations below this line
 */
class AddControllerCest
{
    /**
     * @param \SprykerTest\Zed\Glossary\GlossaryCommunicationTester $i
     *
     * @return void
     */
    public function addTranslation(GlossaryCommunicationTester $i)
    {
        $keyTranslationTransfer = (new KeyTranslationBuilder())->build();
        $i->amOnPage('/glossary/add');
        $i->seeResponseCodeIs(200);

        $formData = [
            'translation' => $keyTranslationTransfer->toArray(),
        ];

        $i->submitForm(['name' => 'translation'], $formData);

        $i->seeResponseCodeIs(302);
        $i->amOnPage('/glossary');
    }

    /**
     * @param \SprykerTest\Zed\Glossary\GlossaryCommunicationTester $i
     *
     * @return void
     */
    public function addTranslationWithoutTranslations(GlossaryCommunicationTester $i)
    {
        $keyTranslationTransfer = (new KeyTranslationBuilder())->build();
        $i->amOnPage('/glossary/add');
        $i->seeResponseCodeIs(200);

        $formData = [
            'translation' => [
                'glossary_key' => $keyTranslationTransfer->getGlossaryKey(),
            ],
        ];

        $i->submitForm(['name' => 'translation'], $formData);

        $i->seeResponseCodeIs(302);
        $i->amOnPage('/glossary');
    }
}
