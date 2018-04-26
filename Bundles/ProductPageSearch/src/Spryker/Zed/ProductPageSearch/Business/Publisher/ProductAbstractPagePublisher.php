<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch;
use Spryker\Zed\ProductPageSearch\Business\Exception\PluginNotFoundException;
use Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface;
use Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface;

class ProductAbstractPagePublisher implements ProductAbstractPagePublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[]
     */
    protected $pageDataExpanderPlugins = [];

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    protected $productPageSearchMapper;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface
     */
    protected $productPageSearchWriter;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface $productPageSearchMapper
     * @param \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface $productPageSearchWriter
     */
    public function __construct(
        ProductPageSearchQueryContainerInterface $queryContainer,
        array $pageDataExpanderPlugins,
        ProductPageSearchMapperInterface $productPageSearchMapper,
        ProductPageSearchWriterInterface $productPageSearchWriter
    ) {

        $this->queryContainer = $queryContainer;
        $this->pageDataExpanderPlugins = $pageDataExpanderPlugins;
        $this->productPageSearchMapper = $productPageSearchMapper;
        $this->productPageSearchWriter = $productPageSearchWriter;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $spyProductAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $spyProductAbstractSearchEntities = $this->findProductPageSearchEntitiesByProductAbstractIds($productAbstractIds);

        if (!$spyProductAbstractLocalizedEntities) {
            $this->deleteSearchData($spyProductAbstractSearchEntities);
        }

        $this->storeData($spyProductAbstractLocalizedEntities, $spyProductAbstractSearchEntities);
    }

    /**
     * @param array $productAbstractIds
     * @param array $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = [])
    {
        if (!empty($pageDataExpanderPluginNames)) {
            $this->pageDataExpanderPlugins = $this->getPageDataExpanderPlugins($pageDataExpanderPluginNames);
        }

        $spyProductAbstractLocalizedEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $spyProductAbstractSearchEntities = $this->findProductPageSearchEntitiesByProductAbstractIds($productAbstractIds);

        $this->storeData($spyProductAbstractLocalizedEntities, $spyProductAbstractSearchEntities, true);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $spyProductPageSearchEntities = $this->findProductPageSearchEntitiesByProductAbstractIds($productAbstractIds);
        $this->deleteSearchData($spyProductPageSearchEntities);
    }

    /**
     * @param array $spyProductAbstractSearchEntities
     *
     * @return void
     */
    protected function deleteSearchData(array $spyProductAbstractSearchEntities)
    {
        foreach ($spyProductAbstractSearchEntities as $spyProductAbstractSearchLocalizedEntities) {
            foreach ($spyProductAbstractSearchLocalizedEntities as $spyProductAbstractSearchLocalizedEntity) {
                $spyProductAbstractSearchLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractSearchEntities
     * @param bool $isRefresh
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractSearchEntities, $isRefresh = false)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity['fk_product_abstract'];
            $localeName = $spyProductAbstractLocalizedEntity['Locale']['locale_name'];
            if (isset($spyProductAbstractSearchEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $spyProductAbstractSearchEntities[$idProduct][$localeName], $isRefresh);
            } elseif (!$isRefresh) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, null, false);
            }
        }
    }

    /**
     * @param array $spyProductAbstractLocalizedEntity
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch|null $spyProductPageSearchEntity
     * @param bool $isRefresh
     *
     * @return void
     */
    protected function storeDataSet(array $spyProductAbstractLocalizedEntity, ?SpyProductAbstractPageSearch $spyProductPageSearchEntity = null, $isRefresh = false)
    {
        if (!$this->isActive($spyProductAbstractLocalizedEntity)) {
            if (!$spyProductPageSearchEntity === null) {
                $spyProductPageSearchEntity->delete();
            }

            return;
        }

        $productPageSearchTransfer = $this->getProductPageSearchTransfer($spyProductAbstractLocalizedEntity, $spyProductPageSearchEntity, $isRefresh);
        $data = $this->productPageSearchMapper->mapToSearchData($productPageSearchTransfer);

        $this->productPageSearchWriter->save($productPageSearchTransfer, $data, $spyProductPageSearchEntity);
    }

    /**
     * @param array $spyProductAbstractLocalizedEntity
     *
     * @return bool
     */
    protected function isActive(array $spyProductAbstractLocalizedEntity)
    {
        foreach ($spyProductAbstractLocalizedEntity['SpyProductAbstract']['SpyProducts'] as $spyProduct) {
            if ($spyProduct['is_active']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $productAbstractLocalizedData
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch|null $productPageSearchEntity
     * @param bool $isRefresh
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer
     */
    protected function getProductPageSearchTransfer(array $productAbstractLocalizedData, ?SpyProductAbstractPageSearch $productPageSearchEntity = null, $isRefresh = false)
    {
        if ($isRefresh) {
            $productPageSearchTransfer = $this->productPageSearchMapper->mapToProductPageSearchTransferFromJson($productPageSearchEntity->getStructuredData());
        } else {
            $productPageSearchTransfer = $this->productPageSearchMapper->mapToProductPageSearchTransfer($productAbstractLocalizedData);
        }

        $this->expandTransferWithPlugins($productAbstractLocalizedData, $productPageSearchTransfer);

        return $productPageSearchTransfer;
    }

    /**
     * @param array $pageDataExpanderPluginNames
     *
     * @return array
     */
    protected function getPageDataExpanderPlugins(array $pageDataExpanderPluginNames)
    {
        $expanderPlugins = [];
        foreach ($pageDataExpanderPluginNames as $pageDataExpanderPluginName) {
            $this->checkHasPlugin($pageDataExpanderPluginName);
            $expanderPlugins[] = $this->pageDataExpanderPlugins[$pageDataExpanderPluginName];
        }

        return $expanderPlugins;
    }

    /**
     * @param string $pageDataExpanderPluginName
     *
     * @throws \Spryker\Zed\ProductPageSearch\Business\Exception\PluginNotFoundException
     *
     * @return void
     */
    protected function checkHasPlugin($pageDataExpanderPluginName)
    {
        if (!isset($this->pageDataExpanderPlugins[$pageDataExpanderPluginName])) {
            throw new PluginNotFoundException(sprintf('The plugin with this name: %s is not found', $pageDataExpanderPluginName));
        }
    }

    /**
     * @param array $productAbstractLocalizedData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     *
     * @return void
     */
    protected function expandTransferWithPlugins(array $productAbstractLocalizedData, ProductPageSearchTransfer $productPageSearchTransfer)
    {
        foreach ($this->pageDataExpanderPlugins as $productPageDataExpanderPlugin) {
            $productPageDataExpanderPlugin->expandProductPageData($productAbstractLocalizedData, $productPageSearchTransfer);
        }
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->queryContainer->queryProductAbstractByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductPageSearchEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractSearchEntities = $this->queryContainer->queryProductAbstractSearchPageByIds($productAbstractIds)->find();
        $productAbstractSearchEntitiesByIdAndLocale = [];
        foreach ($productAbstractSearchEntities as $productAbstractSearchEntity) {
            $productAbstractSearchEntitiesByIdAndLocale[$productAbstractSearchEntity->getFkProductAbstract()][$productAbstractSearchEntity->getLocale()] = $productAbstractSearchEntity;
        }

        return $productAbstractSearchEntitiesByIdAndLocale;
    }
}
