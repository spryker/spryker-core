<?php

namespace SprykerFeature\Zed\SearchPage\Business\Processor;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class SearchPageConfigProcessor implements SearchPageConfigProcessorInterface
{

    /**
     * @var KeyBuilderInterface
     */
    private $searchPageConfigKeyBuilder;

    /**
     * @param KeyBuilderInterface $searchPageConfigKeyBuilder
     */
    public function __construct(KeyBuilderInterface $searchPageConfigKeyBuilder)
    {
        $this->searchPageConfigKeyBuilder = $searchPageConfigKeyBuilder;
    }

    /**
     * @param array $configRaw
     * @param LocaleDto $localeDto
     *
     * @return array
     */
    public function processSearchPageConfig(array $configRaw, LocaleDto $localeDto)
    {
        $localeName = $localeDto->getLocaleName();
        $storageKey = $this->searchPageConfigKeyBuilder->generateKey('', $localeName);
        $pageConfig = [
            $storageKey => $this->groupByType($configRaw),
        ];

        return $pageConfig;
    }

    /**
     * @param array $configRaw
     *
     * @return array
     */
    private function groupByType(array $configRaw)
    {
        $groupedElements = [];

        foreach ($configRaw as $pageElement) {
            $attributeType = $pageElement['attribute_type'];
            if (!array_key_exists($attributeType, $groupedElements)) {
                $groupedElements[$attributeType] = [];
            }
            $groupedElements[$attributeType][] = $pageElement;
        }

        return $groupedElements;
    }
}
