<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Translator;

use Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface;
use Symfony\Component\Translation\Formatter\MessageFormatterInterface;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator extends SymfonyTranslator implements TranslatorInterface
{
    /**
     * @var string
     */
    protected static $locale;

    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface
     */
    protected $translatorBuilder;

    /**
     * @var bool
     */
    protected $resourcesInitialised = false;

    /**
     * @param \Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface $translatorBuilder
     * @param string $locale
     * @param \Symfony\Component\Translation\Formatter\MessageFormatterInterface|null $formatter
     * @param string|null $cacheDir
     * @param bool $debug
     */
    public function __construct(TranslatorBuilderInterface $translatorBuilder, string $locale, ?MessageFormatterInterface $formatter = null, ?string $cacheDir = null, bool $debug = false)
    {
        parent::__construct($locale, $formatter, $cacheDir, $debug);

        $this->translatorBuilder = $translatorBuilder;
    }

    /**
     * @param string $locale
     *
     * @return void
     */
    protected function initializeCatalogue($locale)
    {
        if (!$this->resourcesInitialised) {
            $this->translatorBuilder->buildTranslator($this);
            $this->resourcesInitialised = true;
        }

        parent::initializeCatalogue($locale);
    }

    /**
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale): void
    {
        $this->assertValidLocale($locale);
        static::$locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return static::$locale;
    }

    /**
     * @param string $keyName
     * @param string $locale
     *
     * @return bool
     */
    public function has(string $keyName, string $locale): bool
    {
        return $this->getCatalogue($locale)->defines($keyName);
    }
}
