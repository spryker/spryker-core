<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Translation;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Translation\Business\TranslationFacade;

class TranslationTest extends Test
{

    protected $translationFacade;

    protected function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();

        $this->translationFacade = new TranslationFacade(
            new Factory('Translation'),
            $locator
        );
    }

    public function testGetTranslator()
    {
        $translator = $this->translationFacade->getTranslator('de');

        $this->assertInstanceOf(
            'SprykerEngine\Zed\Translation\Business\Translator',
            $translator
        );
    }

}
