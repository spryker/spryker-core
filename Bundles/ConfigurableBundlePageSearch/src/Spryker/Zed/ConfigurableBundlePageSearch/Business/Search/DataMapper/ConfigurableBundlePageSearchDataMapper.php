<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business\Search\DataMapper;

use Exception;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\SearchResultDataMapTransfer;
use Laminas\Filter\FilterInterface;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;

class ConfigurableBundlePageSearchDataMapper implements ConfigurableBundlePageSearchDataMapperInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplateMapExpanderPluginInterface[]
     */
    protected $configurableBundleTemplatePageMapExpanderPlugins;

    /**
     * @var \Laminas\Filter\FilterInterface
     */
    protected $underscoreToDashFilter;

    /**
     * @param \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplateMapExpanderPluginInterface[] $configurableBundleTemplatePageMapExpanderPlugins
     * @param \Laminas\Filter\FilterInterface $underscoreToDashFilter
     */
    public function __construct(
        array $configurableBundleTemplatePageMapExpanderPlugins,
        FilterInterface $underscoreToDashFilter
    ) {
        $this->configurableBundleTemplatePageMapExpanderPlugins = $configurableBundleTemplatePageMapExpanderPlugins;
        $this->underscoreToDashFilter = $underscoreToDashFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapConfigurableBundleTemplatePageSearchTransferToSearchData(
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer,
        LocaleTransfer $localeTransfer
    ): array {
        $pageMapTransfer = (new PageMapTransfer())
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME);

        $this->addSearchResultData($pageMapTransfer, ConfigurableBundleTemplateTransfer::ID_CONFIGURABLE_BUNDLE_TEMPLATE, $configurableBundleTemplatePageSearchTransfer->getFkConfigurableBundleTemplate())
            ->addSearchResultData($pageMapTransfer, ConfigurableBundleTemplatePageSearchTransfer::UUID, $configurableBundleTemplatePageSearchTransfer->getUuid())
            ->addSearchResultData($pageMapTransfer, ConfigurableBundleTemplatePageSearchTransfer::NAME, $configurableBundleTemplatePageSearchTransfer->getName())
            ->addSearchResultData($pageMapTransfer, ConfigurableBundleTemplatePageSearchTransfer::IMAGES, $configurableBundleTemplatePageSearchTransfer->getImages());

        $pageMapTransfer = $this->expandPageMapWithPlugins($pageMapTransfer, $configurableBundleTemplatePageSearchTransfer, $localeTransfer);

        $result = [];

        $pageIndexMapProperties = $this->getPageIndexMapProperties();

        foreach ($pageMapTransfer->modifiedToArray() as $key => $value) {
            $normalizedKey = $this->normalizeKey($key, $pageIndexMapProperties);

            $result = $this->mapValue($pageMapTransfer, $normalizedKey, $value, $result);
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function expandPageMapWithPlugins(
        PageMapTransfer $pageMapTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        foreach ($this->configurableBundleTemplatePageMapExpanderPlugins as $configurableBundleTemplatePageMapExpanderPlugin) {
            $configurableBundleTemplatePageMapExpanderPlugin->expand($pageMapTransfer, $configurableBundleTemplatePageSearchTransfer, $localeTransfer);
        }

        return $pageMapTransfer;
    }

    /**
     * @param string $key
     * @param array $pageIndexMapProperties
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function normalizeKey(string $key, array $pageIndexMapProperties): string
    {
        if (in_array($key, $pageIndexMapProperties)) {
            return $key;
        }

        $normalizedKey = $this->underscoreToDashFilter->filter($key);

        if (in_array($normalizedKey, $pageIndexMapProperties)) {
            return $normalizedKey;
        }

        throw new Exception(sprintf('Unable to map %s property in %s', $key, PageIndexMap::class));
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $normalizedKey
     * @param mixed $value
     * @param array $result
     *
     * @return array
     */
    protected function mapValue(PageMapTransfer $pageMapTransfer, string $normalizedKey, $value, array $result): array
    {
        if ($normalizedKey === PageIndexMap::SEARCH_RESULT_DATA) {
            return $this->transformSearchResultData($result, $pageMapTransfer->getSearchResultData());
        }

        $result[$normalizedKey] = $value;

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\SearchResultDataMapTransfer[]|\ArrayObject $searchResultData
     *
     * @return mixed[]
     */
    protected function transformSearchResultData(array $result, $searchResultData): array
    {
        foreach ($searchResultData as $searchResultDataMapTransfer) {
            $searchResultDataMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::SEARCH_RESULT_DATA][$searchResultDataMapTransfer->getName()] = $searchResultDataMapTransfer->getValue();
        }

        return $result;
    }

    /**
     * @return string[]
     */
    protected function getPageIndexMapProperties(): array
    {
        return (new PageIndexMap())->getProperties();
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    protected function addSearchResultData(PageMapTransfer $pageMapTransfer, string $name, $value)
    {
        $searchResultTransfer = (new SearchResultDataMapTransfer())
            ->setName($name)
            ->setValue($value);

        $pageMapTransfer->addSearchResultData($searchResultTransfer);

        return $this;
    }
}
