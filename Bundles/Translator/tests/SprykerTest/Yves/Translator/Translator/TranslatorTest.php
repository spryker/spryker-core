<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Translator\Translator;

use Codeception\Test\Unit;
use InvalidArgumentException;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientBridge;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientInterface;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientBridge;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientInterface;
use Spryker\Yves\Translator\Translator\Translator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Translator
 * @group Translator
 * @group TranslatorTest
 * Add your own group annotations below this line
 */
class TranslatorTest extends Unit
{
    /**
     * @var string
     */
    protected const TRANSLATION_KEY_PREFIX = 'translation:en_us:';

    /**
     * @var string
     */
    protected const NON_EXISTIN_GLOSSARY_KEY = 'non-existing-glossary-key';

    /**
     * @var string
     */
    protected const FALLBACK_GLOSSARY_KEY = 'fallback-translation';

    /**
     * @var string
     */
    protected const STORAGE_WITH_FALLBACK_TRANSLATION = '{"id_glossary_translation":435,"fk_glossary_key":218,"fk_locale":66,"is_active":true,"value":"Fallback successful!","glossary_key":{"id_glossary_key":218,"is_active":true,"key":"fallback-translation"},"locale":{"id_locale":66,"is_active":true,"locale_name":"en_US"},"GlossaryKey":{"id_glossary_key":218,"is_active":true,"key":"fallback-translation"},"Locale":{"id_locale":66,"is_active":true,"locale_name":"en_US"},"_timestamp":1692262243.609153}';

    /**
     * @var \SprykerTest\Yves\Translator\TranslatorYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testTransWithANonExistingGlossaryKeyWillReturnKeyAsTranslation(): void
    {
        // Arrange
        $translator = $this->getTranslator();
        $this->assertGlossaryKeyDoesNotExist(static::NON_EXISTIN_GLOSSARY_KEY);

        // Act
        $result = $translator->trans(static::NON_EXISTIN_GLOSSARY_KEY);

        // Assert
        $this->assertSame(static::NON_EXISTIN_GLOSSARY_KEY, $result);
    }

    /**
     * @return void
     */
    public function testTransWithFallbackTranslation(): void
    {
        // Arrange
        $translator = $this->getTranslator();
        $this->tester->getStorageClient()->set('translation:en_us:fallback-translation', static::STORAGE_WITH_FALLBACK_TRANSLATION);

        // Act
        $result = $translator->trans(static::NON_EXISTIN_GLOSSARY_KEY, [
            '__forward_compatibility_translation' => [static::FALLBACK_GLOSSARY_KEY],
        ]);

        // Assert
        $this->assertSame('Fallback successful!', $result);
    }

    /**
     * @return void
     */
    public function testTransFallbackExceptionWhenAnEmptyArrayIsProvided(): void
    {
        // Arrange
        $glossaryClientMock = $this->getGlossaryClientMock();
        $localeClientMock = $this->getLocaleClientMock();
        $translator = new Translator($glossaryClientMock, $localeClientMock);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('__forward_compatibility_translation must be an array with at least one element.');

        // Act
        $translator->trans('test', ['__forward_compatibility_translation' => []]);
    }

    /**
     * @param string $glossaryKey
     *
     * @return void
     */
    protected function assertGlossaryKeyDoesNotExist(string $glossaryKey): void
    {
        $this->tester->assertStorageNotHasKey(static::TRANSLATION_KEY_PREFIX . $glossaryKey);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientInterface
     */
    protected function getGlossaryClientMock(): MockObject|TranslatorToGlossaryStorageClientInterface
    {
        return $this->getMockBuilder(TranslatorToGlossaryStorageClientInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientInterface
     */
    protected function getLocaleClientMock(): MockObject|TranslatorToLocaleClientInterface
    {
        return $this->getMockBuilder(TranslatorToLocaleClientInterface::class)
            ->getMock();
    }

    /**
     * @return \Spryker\Yves\Translator\Translator\Translator
     */
    protected function getTranslator(): Translator
    {
        $translatorToGlossaryStorageClientBridge = new TranslatorToGlossaryStorageClientBridge($this->tester->getLocator()->glossaryStorage()->client());
        $translatorToLocaleClientBridge = new TranslatorToLocaleClientBridge($this->tester->getLocator()->locale()->client());

        return new Translator(
            $translatorToGlossaryStorageClientBridge,
            $translatorToLocaleClientBridge,
        );
    }
}
