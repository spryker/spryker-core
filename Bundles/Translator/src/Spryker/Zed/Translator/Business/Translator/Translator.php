<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Translator;

use Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface;
use Spryker\Zed\Translator\TranslatorConfig;
use Symfony\Component\Translation\Formatter\MessageFormatterInterface;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator extends SymfonyTranslator implements TranslatorInterface
{
    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface
     */
    protected $translatorBuilder;

    /**
     * @var bool
     */
    protected $resourcesInitialised = false;

    /**
     * @var \Spryker\Zed\Translator\TranslatorConfig
     */
    protected $translatorConfig;

    /**
     * @param \Spryker\Zed\Translator\Business\TranslatorBuilder\TranslatorBuilderInterface $translatorBuilder
     * @param string $locale
     * @param \Spryker\Zed\Translator\TranslatorConfig $translatorConfig
     * @param \Symfony\Component\Translation\Formatter\MessageFormatterInterface|null $formatter
     */
    public function __construct(
        TranslatorBuilderInterface $translatorBuilder,
        string $locale,
        TranslatorConfig $translatorConfig,
        ?MessageFormatterInterface $formatter = null
    ) {
        parent::__construct(
            $locale,
            $formatter,
            $translatorConfig->getTranslatorCacheDirectory(),
            $translatorConfig->isZedTranslatorDebugEnabled()
        );

        $this->translatorBuilder = $translatorBuilder;
        $this->translatorConfig = $translatorConfig;
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
        parent::setLocale($locale);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return parent::getLocale();
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

    /**
     * @param mixed $locale
     *
     * @return array
     */
    protected function computeFallbackLocales($locale): array
    {
        $locales = parent::computeFallbackLocales($locale);
        $configFalbackLocales = $this->translatorConfig->getFallbackLocales($locale);

        return array_unique(array_merge($locales, $configFalbackLocales));
    }
}
