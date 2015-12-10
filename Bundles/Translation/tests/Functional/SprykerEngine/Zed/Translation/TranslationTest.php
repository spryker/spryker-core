<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Translation;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Translation\Business\TranslationFacade;

class TranslationTest extends Test
{

    protected $translationFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->translationFacade = new TranslationFacade();
    }

    /**
     * @return void
     */
    public function testGetTranslator()
    {
        $translator = $this->translationFacade->getTranslator('de');

        $this->assertInstanceOf(
            'SprykerEngine\Zed\Translation\Business\Translator',
            $translator
        );
    }

}
