<?php

namespace SprykerFeature\Zed\GlossaryExporter\Communication\Plugin;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\GlossaryExporter\Communication\GlossaryExporterDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;

/**
 * @method GlossaryExporterDependencyContainer getDependencyContainer()
 */
class TranslationProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{
    /**
     * @var KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);
        $this->keyBuilder = $this->getDependencyContainer()->getKeyBuilder();
    }


    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'translation';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleDto $locale)
    {
        foreach ($resultSet as $index => $translation) {
            $key = $this->keyBuilder->generateKey($translation['translation_key'], $locale);
            $processedResultSet[$key] = $translation['translation_value'];
            $resultSet[$key] = $translation;
            unset($resultSet[$index]);
        }

        return $processedResultSet;
    }
}
