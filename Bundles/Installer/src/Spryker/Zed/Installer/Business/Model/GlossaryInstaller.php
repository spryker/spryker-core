<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Installer\Dependency\Facade\InstallerToGlossaryInterface;
use Symfony\Component\Yaml\Yaml;

class GlossaryInstaller extends AbstractInstaller
{

    /**
     * @var \Spryker\Zed\Installer\Dependency\Facade\InstallerToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var array
     */
    protected $paths;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected $yamlParser;

    /**
     * @param \Spryker\Zed\Installer\Dependency\Facade\InstallerToGlossaryInterface $glossaryFacade
     * @param array $paths
     */
    public function __construct(InstallerToGlossaryInterface $glossaryFacade, array $paths = [])
    {
        $this->glossaryFacade = $glossaryFacade;
        $this->paths = $paths;
        $this->yamlParser = new Yaml();
    }

    /**
     * @return array
     */
    public function install()
    {
        $results = [];

        foreach ($this->paths as $filePath) {
            $translations = $this->parseYamlFile($filePath);
            $result = $this->installKeysAndTranslations($translations);
            $results[$filePath] = $result;
        }

        return $results;
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    protected function parseYamlFile($filePath)
    {
        return $this->yamlParser->parse(file_get_contents($filePath));
    }

    /**
     * @param array $translations
     *
     * @return array
     */
    protected function installKeysAndTranslations(array $translations)
    {
        $results = [];
        foreach ($translations['keys'] as $keyName => $data) {
            $results[$keyName]['created'] = false;
            if (!$this->glossaryFacade->hasKey($keyName)) {
                $this->glossaryFacade->createKey($keyName);
                $results[$keyName]['created'] = true;
            }

            foreach ($data['translations'] as $localeName => $text) {
                $locale = new LocaleTransfer();
                $locale->setLocaleName($localeName);
                $results[$keyName]['translation'][$localeName]['text'] = $text;
                $results[$keyName]['translation'][$localeName]['created'] = false;
                $results[$keyName]['translation'][$localeName]['updated'] = false;

                if (!$this->glossaryFacade->hasTranslation($keyName, $locale)) {
                    $this->glossaryFacade->createAndTouchTranslation($keyName, $locale, $text, true);
                    $results[$keyName]['translation'][$localeName]['created'] = true;
                } elseif ($this->glossaryFacade->getTranslation($keyName, $locale)->getValue() !== $text) {
                    $this->glossaryFacade->updateAndTouchTranslation($keyName, $locale, $text, true);
                    $results[$keyName]['translation'][$localeName]['updated'] = true;
                }
            }
        }

        return $results;
    }

}
