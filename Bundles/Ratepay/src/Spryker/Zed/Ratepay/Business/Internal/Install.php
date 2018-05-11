<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Internal;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryInterface;
use Spryker\Zed\Ratepay\RatepayConfig;
use Symfony\Component\Yaml\Yaml;

class Install implements InstallInterface
{
    /**
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryInterface
     */
    protected $glossary;

    /**
     * @var \Spryker\Zed\Ratepay\RatepayConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryInterface $glossary
     * @param \Spryker\Zed\Ratepay\RatepayConfig $config
     */
    public function __construct(RatepayToGlossaryInterface $glossary, RatepayConfig $config)
    {
        $this->glossary = $glossary;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function install()
    {
        $fileName = $this->config->getTranslationFilePath();
        $translations = $this->parseYamlFile($fileName);

        return $this->installKeysAndTranslations($translations);
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    protected function parseYamlFile($filePath)
    {
        $yamlParser = new Yaml();

        return $yamlParser->parse(file_get_contents($filePath));
    }

    /**
     * @param array $translations
     *
     * @return array
     */
    protected function installKeysAndTranslations(array $translations)
    {
        $results = [];
        foreach ($translations as $keyName => $data) {
            $results[$keyName]['created'] = false;
            if (!$this->glossary->hasKey($keyName)) {
                $this->glossary->createKey($keyName);
                $results[$keyName]['created'] = true;
            }

            foreach ($data['translations'] as $localeName => $text) {
                $results[$keyName]['translation'][$localeName] = $this->addTranslation($localeName, $keyName, $text);
            }
        }

        return $results;
    }

    /**
     * @param string $localeName
     * @param string $keyName
     * @param string $text
     *
     * @return array
     */
    protected function addTranslation($localeName, $keyName, $text)
    {
        $locale = new LocaleTransfer();
        $locale->setLocaleName($localeName);
        $translation = [];

        $translation['text'] = $text;
        $translation['created'] = false;
        $translation['updated'] = false;

        if (!$this->glossary->hasTranslation($keyName, $locale)) {
            $this->glossary->createAndTouchTranslation($keyName, $locale, $text, true);
            $translation['created'] = true;

            return $translation;
        }

        $this->glossary->updateAndTouchTranslation($keyName, $locale, $text, true);
        $translation['updated'] = true;

        return $translation;
    }
}
