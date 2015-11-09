<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Zed\Translation\Business\TranslationFileFinder;
use SprykerEngine\Zed\Translation\Business\TranslationFileLoaderFactory;
use SprykerEngine\Zed\Translation\Business\Translator;
use SprykerEngine\Zed\Translation\Business\TranslatorInterface;
use Symfony\Component\Translation\Loader\PoFileLoader;

class TranslatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    protected function setUp()
    {
        $this->translator = new Translator(
            'de',
            new TranslationFileFinder([]),
            new TranslationFileLoaderFactory()
        );
        $this->translator->addLoader('po', new PoFileLoader());

        $this->translator->addResource(
            'po',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/Translation/tests/_data/de.po',
            'de'
        );
    }

    public function testTrans()
    {
        // normal
        $this->assertEquals(
            'Foo Text',
            $this->translator->trans('foo')
        );

        // fallback
        $this->assertEquals(
            'Foo Text',
            $this->translator->trans('test.foo')
        );

        // undefined
        $this->assertEquals(
            'foo.bar',
            $this->translator->trans('foo.bar')
        );
    }

    public function testTransChoice()
    {
        $this->assertEquals(
            'one',
            $this->translator->transChoice('choice', 1)
        );

        $this->assertEquals(
            'more',
            $this->translator->transChoice('choice', 2)
        );
    }

    public function testTransShouldReturnString()
    {
        $this->assertInternalType(
            'string',
            $this->translator->trans('foo')
        );
    }

    public function testTransChoiceShouldReturnString()
    {
        $this->assertInternalType(
            'string',
            $this->translator->transChoice('choice', 1)
        );
    }

}
