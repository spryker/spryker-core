<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

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
     * @param LocaleTransfer $localeDto
     *
     * @return array
     */
    public function processSearchPageConfig(array $configRaw, LocaleTransfer $localeDto)
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
