<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null)
    {
        return $this
            ->getFactory()
            ->createSearchIndexManager()
            ->getTotalCount();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null)
    {
        return $this
            ->getFactory()
            ->createSearchIndexManager($indexName)
            ->getMetaData();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return \Elastica\Response
     */
    public function delete(?string $indexName = null)
    {
        return $this
            ->getFactory()
            ->createSearchIndexManager($indexName)
            ->delete();
    }

    /**
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @deprecated Use transformPageMapToDocumentByMapperName() instead.
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
     *
     * @
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository($repositoryName)
    {
        return $this->getFactory()->createSnapshotHandler()->existsSnapshotRepository($repositoryName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function closeIndex()
    {
        return $this->getFactory()->createSearchIndexManager()->close();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function openIndex(): bool
    {
        return $this->getFactory()->createSearchIndexManager()->open();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function closeAllIndices()
    {
        return $this->getFactory()->createSearchIndicesManager()->close();
    }

    /**
     * {@inheritdoc}
     *
     * @api
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
}
