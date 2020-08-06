<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @method \Spryker\Zed\Search\Business\SearchBusinessFactory getFactory()
 */
class SearchFacade extends AbstractFacade implements SearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function install(LoggerInterface $messenger)
    {
        $this
            ->getFactory()
            ->createSearchInstaller($messenger)
            ->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use corresponding API from search provider-specific modules (e.g. spryker/search-elasticsearch) instead.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this
            ->getFactory()
            ->createSearchIndexManager()
            ->getTotalCount();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use corresponding API from search provider-specific modules (e.g. spryker/search-elasticsearch) instead.
     *
     * @return array
     */
    public function getMetaData()
    {
        return $this
            ->getFactory()
            ->createSearchIndexManager()
            ->getMetaData();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::deleteIndex()} instead.
     *
     * @return \Elastica\Response
     */
    public function delete()
    {
        return $this
            ->getFactory()
            ->createSearchIndexManager()
            ->delete();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type)
    {
        return $this
            ->getFactory()
            ->createSearchIndexManager()
            ->getDocument($key, $type);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array|\Elastica\ResultSet|mixed (@deprecated Only mixed will be supported with the next major)
     */
    public function searchKeys($searchString, $limit = null, $offset = null)
    {
        return $this
            ->getFactory()
            ->getSearchClient()
            ->searchKeys($searchString, $limit, $offset);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidPropertyNameException
     *
     * @return array
     */
    public function transformPageMapToDocument(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createPageDataMapper()
            ->mapData($pageMap, $data, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $mapperName
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidPropertyNameException
     *
     * @return array
     */
    public function transformPageMapToDocumentByMapperName(array $data, LocaleTransfer $localeTransfer, $mapperName)
    {
        return $this->getFactory()
            ->createPageDataMapper()
            ->transferDataByMapperName($data, $localeTransfer, $mapperName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Search\Business\SearchFacade::generateSourceMap()} instead.
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generatePageIndexMap(LoggerInterface $messenger)
    {
        $this->getFactory()
            ->createIndexMapInstaller($messenger)
            ->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateSourceMap(LoggerInterface $messenger): void
    {
        $this->getFactory()
            ->createSourceMapInstaller($messenger)
            ->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::createSnapshot()} instead.
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function createSnapshot($repositoryName, $snapshotName, $options = [])
    {
        return $this->getFactory()->createSnapshotHandler()->createSnapshot($repositoryName, $snapshotName, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::existsSnapshot()} instead.
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshot($repositoryName, $snapshotName)
    {
        return $this->getFactory()->createSnapshotHandler()->existsSnapshot($repositoryName, $snapshotName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::deleteSnapshot()} instead.
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function deleteSnapshot($repositoryName, $snapshotName)
    {
        return $this->getFactory()->createSnapshotHandler()->deleteSnapshot($repositoryName, $snapshotName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::existsSnapshotRepository()} instead.
     *
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository($repositoryName)
    {
        return $this->getFactory()->createSnapshotHandler()->existsSnapshotRepository($repositoryName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::createSnapshotRepository()} instead.
     *
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return bool
     */
    public function createSnapshotRepository($repositoryName, $type = 'fs', $settings = [])
    {
        return $this->getFactory()->createSnapshotHandler()->registerSnapshotRepository($repositoryName, $type, $settings);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::restoreSnapshot()} instead.
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshot($repositoryName, $snapshotName, $options = [])
    {
        return $this->getFactory()->createSnapshotHandler()->restoreSnapshot($repositoryName, $snapshotName, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::closeIndex()} instead.
     *
     * @return bool
     */
    public function closeIndex()
    {
        return $this->getFactory()->createSearchIndexManager()->close();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::openIndex()} instead.
     *
     * @return bool
     */
    public function openIndex(): bool
    {
        return $this->getFactory()->createSearchIndexManager()->open();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::closeIndex()} instead.
     *
     * @return bool
     */
    public function closeAllIndices()
    {
        return $this->getFactory()->createSearchIndicesManager()->close();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacade::copyIndex()} instead.
     *
     * @param string $source
     * @param string $target
     *
     * @return bool
     */
    public function copyIndex($source, $target)
    {
        return $this->getFactory()->createElasticsearchIndexCopier()->copyIndex($source, $target);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Search\Business\SearchFacade::installSources()} instead.
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function installIndexes(LoggerInterface $messenger): void
    {
        $this->getFactory()->createElasticsearchIndexInstaller($messenger)->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function installSources(LoggerInterface $messenger): void
    {
        $this->getFactory()->createSearchSourceInstaller($messenger)->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeSearchHealthCheck(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createSearchHealthChecker()->executeHealthCheck();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be remove without replacement.
     *
     * @return bool
     */
    public function isInLegacyMode(): bool
    {
        return $this->getFactory()->createSearchLegacyModeChecker()->isInLegacyMode();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function removeSourceMap(): void
    {
        $this->getFactory()->createElasticsearchIndexMapCleaner()->cleanDirectory();
    }
}
