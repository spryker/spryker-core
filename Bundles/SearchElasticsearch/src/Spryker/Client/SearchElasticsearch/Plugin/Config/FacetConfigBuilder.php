<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\Config;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Config\FacetConfigBuilderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchConfigBuilderPluginInterface;

class FacetConfigBuilder extends AbstractPlugin implements FacetConfigBuilderInterface
{
    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    protected $facetConfigTransfers = [];

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchConfigBuilderPluginInterface|null $facetSearchConfigBuilderPlugin
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $searchConfigExpanderPlugins
     */
    public function __construct(?FacetSearchConfigBuilderPluginInterface $facetSearchConfigBuilderPlugin = null, array $searchConfigExpanderPlugins = [])
    {
        $this->buildFacetConfig($facetSearchConfigBuilderPlugin);
        $this->expandFacetConfig($searchConfigExpanderPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return $this
     */
    public function addFacet(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->assertFacetConfigTransfer($facetConfigTransfer);

        $this->facetConfigTransfers[$facetConfigTransfer->getName()] = $facetConfigTransfer;

        return $this;
    }

    /**
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer|null
     */
    public function get($facetName): ?FacetConfigTransfer
    {
        return isset($this->facetConfigTransfers[$facetName]) ? $this->facetConfigTransfers[$facetName] : null;
    }

    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getAll(): array
    {
        return $this->facetConfigTransfers;
    }

    /**
     * @return array
     */
    public function getParamNames(): array
    {
        return array_keys($this->facetConfigTransfers);
    }

    /**
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer[]
     */
    public function getActive(array $requestParameters): array
    {
        $activeFacets = [];

        foreach ($this->facetConfigTransfers as $facetName => $facetConfigTransfer) {
            if (array_key_exists($facetConfigTransfer->getParameterName(), $requestParameters)) {
                $activeFacets[$facetName] = $facetConfigTransfer;
            }
        }

        return $activeFacets;
    }

    /**
     * @param array $requestParameters
     *
     * @return string[]
     */
    public function getActiveParamNames(array $requestParameters): array
    {
        return array_keys($this->getActive($requestParameters));
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return void
     */
    protected function assertFacetConfigTransfer(FacetConfigTransfer $facetConfigTransfer): void
    {
        $facetConfigTransfer
            ->requireName()
            ->requireFieldName()
            ->requireParameterName()
            ->requireType();
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\FacetSearchConfigBuilderPluginInterface|null $facetSearchConfigBuilderPlugin
     *
     * @return void
     */
    protected function buildFacetConfig(?FacetSearchConfigBuilderPluginInterface $facetSearchConfigBuilderPlugin = null): void
    {
        if (!$facetSearchConfigBuilderPlugin) {
            return;
        }

        $facetSearchConfigBuilderPlugin->buildFacetConfig($this);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[] $searchConfigExpanderPlugins
     *
     * @return void
     */
    protected function expandFacetConfig(array $searchConfigExpanderPlugins): void
    {
        foreach ($searchConfigExpanderPlugins as $searchConfigExpanderPlugin) {
            $searchConfigExtensionTransfer = $searchConfigExpanderPlugin->getSearchConfigExtension();
            $this->addFacetConfigToFacetConfigBuilder($searchConfigExtensionTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigExtensionTransfer $searchConfigExtensionTransfer
     *
     * @return void
     */
    protected function addFacetConfigToFacetConfigBuilder(SearchConfigExtensionTransfer $searchConfigExtensionTransfer): void
    {
        foreach ($searchConfigExtensionTransfer->getFacetConfigs() as $facetConfigTransfer) {
            $this->addFacet($facetConfigTransfer);
        }
    }
}
