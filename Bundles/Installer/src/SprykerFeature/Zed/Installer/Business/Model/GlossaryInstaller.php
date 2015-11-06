<?php
/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Installer\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use Symfony\Component\Yaml\Yaml;

class GlossaryInstaller extends AbstractInstaller
{

    protected $glossaryFacade;

    protected $paths;

    protected $yamlParser;

    /**
     * @param GlossaryFacade $glossaryFacade
     * @param array $paths
     */
    public function __construct(GlossaryFacade $glossaryFacade, array $paths = [])
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
