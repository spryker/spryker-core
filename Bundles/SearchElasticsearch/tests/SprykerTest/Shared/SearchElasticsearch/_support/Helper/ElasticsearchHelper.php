<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SearchElasticsearch\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Elastica\Client;
use Elastica\Document;
use Elastica\Exception\NotFoundException;
use Elastica\Exception\ResponseException;
use Elastica\Index;
use Elastica\Request;
use Elastica\Snapshot;
use Elastica\Type;
use RuntimeException;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig;
use SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper;

class ElasticsearchHelper extends Module
{
    public const DEFAULT_MAPPING_TYPE = 'page';

    protected const REPOSITORY_LOCATION_FILE_NAME = 'search_test_file';
    protected const REPOSITORY_TYPE_FILESYSTEM = 'fs';

    /**
     * @var array
     */
    protected $cleanup = [];

    /**
     * @var \Elastica\Client
     */
    protected static $client;

    /**
     * @param string $indexName
     *
     * @return \Elastica\Index
     */
    public function haveIndex(string $indexName): Index
    {
        $client = $this->getClient();
        $index = $client->getIndex($indexName);

        $this->addCleanup($index);

        if ($index->exists()) {
            return $index;
        }

        $data = [];
        $index->request('', Request::PUT, $data);

        return $index;
    }

    /**
     * @param string $indexName
     *
     * @return void
     */
    public function addCleanupForIndexByName(string $indexName): void
    {
        $client = $this->getClient();
        $index = $client->getIndex($indexName);

        $this->addCleanup($index);
    }

    /**
     * @param \Elastica\Index $index
     *
     * @return void
     */
    protected function addCleanup(Index $index): void
    {
        $this->cleanup[$index->getName()] = function () use ($index) {
            if ($index->exists()) {
                $index->delete();

                return true;
            }

            return false;
        };
    }

    /**
     * @param string $indexName
     * @param string $documentId
     * @param array $documentData
     *
     * @return \Elastica\Index
     */
    public function haveDocumentInIndex(string $indexName, string $documentId = 'foo', array $documentData = ['bar' => 'baz']): Index
    {
        $index = $this->haveIndex($indexName);

        $documents = [
            new Document($documentId, $documentData, static::DEFAULT_MAPPING_TYPE),
        ];

        $index->addDocuments($documents);
        $index->refresh();

        return $index;
    }

    /**
     * @param string $indexName
     *
     * @return void
     */
    public function assertIndexExists(string $indexName): void
    {
        $client = $this->getClient();
        $index = $client->getIndex($indexName);

        $this->assertTrue($index->exists(), sprintf('Index "%s" doesn\'t exist.', $indexName));
    }

    /**
     * @param string $documentId
     * @param string $indexName
     * @param array|string $expectedData
     * @param string $typeName (@deprecated Will be removed once the support for Elasticsearch 6 and lower is dropped.)
     *
     * @return void
     */
    public function assertDocumentExists(string $documentId, string $indexName, $expectedData = [], string $typeName = self::DEFAULT_MAPPING_TYPE): void
    {
        try {
            $document = $this->getDocument($documentId, $indexName, $typeName);

            if ($expectedData) {
                $this->assertEquals($expectedData, $document->getData(), 'Document with id %s exists, but doesn\'t contain expected data.');
            }
        } catch (NotFoundException $e) {
            $this->fail(sprintf('Document with id %s was not found in index %s.', $documentId, $indexName));
        }
    }

    /**
     * @param string $documentId
     * @param string $indexName
     * @param string $mappingType (@deprecated Will be removed once the support for Elasticsearch 6 and lower is dropped.)
     *
     * @return \Elastica\Document
     */
    protected function getDocument(string $documentId, string $indexName, string $mappingType): Document
    {
        if ($this->supportsMappingTypes()) {
            return $this->getClient()->getIndex($indexName)->getType($mappingType)->getDocument($documentId);
        }

        return $this->getClient()->getIndex($indexName)->getDocument($documentId);
    }

    /**
     * @param string $documentId
     * @param string $indexName
     *
     * @return void
     */
    public function assertDocumentDoesNotExist(string $documentId, string $indexName): void
    {
        try {
            $this->getDocument($documentId, $indexName, static::DEFAULT_MAPPING_TYPE);
            $this->fail(sprintf('Document with id %s was found in index %s.', $documentId, $indexName));
        } catch (NotFoundException $e) {
            return;
        }
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        foreach ($this->cleanup as $indexName => $cleanup) {
            $cleanup();
        }
    }

    /**
     * @return \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig
     */
    public function getConfig(): SearchElasticsearchConfig
    {
        return new SearchElasticsearchConfig();
    }

    /**
     * @return \Elastica\Client
     */
    protected function getClient(): Client
    {
        if (!static::$client) {
            static::$client = new Client($this->getConfig()->getClientConfig());
        }

        return static::$client;
    }

    /**
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return void
     */
    public function registerSnapshotRepository(string $repositoryName, string $type = self::REPOSITORY_TYPE_FILESYSTEM, array $settings = []): void
    {
        $snapshot = new Snapshot($this->getClient());
        $settings = array_merge(
            $settings,
            ['location' => $this->getVirtualRepositoryLocation()]
        );
        $snapshot->registerRepository($repositoryName, $type, $settings);
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return void
     */
    public function createSnapshotInRepository(string $repositoryName, string $snapshotName, array $options = []): void
    {
        $snapshot = $this->getSnapshot();

        try {
            $snapshot->getRepository($repositoryName);
        } catch (ResponseException | NotFoundException $exception) {
            $this->registerSnapshotRepository($repositoryName);
        }

        $snapshot->createSnapshot($repositoryName, $snapshotName, $options, true);
        $this->addCleanupForSnapshotInRepository($repositoryName, $snapshotName);
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return void
     */
    public function addCleanupForSnapshotInRepository(string $repositoryName, string $snapshotName): void
    {
        $this->cleanup[] = function () use ($repositoryName, $snapshotName) {
            if ($this->existsSnapshotInRepository($repositoryName, $snapshotName)) {
                $this->getSnapshot()->deleteSnapshot($repositoryName, $snapshotName);
            }
        };
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshotInRepository(string $repositoryName, string $snapshotName): bool
    {
        try {
            $this->getSnapshot()->getSnapshot($repositoryName, $snapshotName);

            return true;
        } catch (RuntimeException $exception) {
            return false;
        }
    }

    /**
     * @return \Elastica\Snapshot
     */
    public function getSnapshot(): Snapshot
    {
        return new Snapshot($this->getClient());
    }

    /**
     * @return string
     */
    public function getVirtualRepositoryLocation(): string
    {
        return $this->getModule('\\' . VirtualFilesystemHelper::class)->getVirtualDirectory() . static::REPOSITORY_LOCATION_FILE_NAME;
    }

    /**
     * @param array $indexParams
     * @param string $mappingTypeName
     *
     * @return \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createIndexMock(array $indexParams = [], string $mappingTypeName = self::DEFAULT_MAPPING_TYPE): Index
    {
        $indexMock = Stub::make(Index::class, $indexParams);

        if ($this->supportsMappingTypes()) {
            $indexMock->method('getType')->willReturn(new Type($indexMock, $mappingTypeName));
        }

        return $indexMock;
    }

    /**
     * @param array $params
     *
     * @return \Elastica\Mapping|\Elastica\Type\Mapping|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createMappingMock(array $params = [])
    {
        if ($this->supportsMappingTypes()) {
            return Stub::make('Elastica\Type\Mapping', $params);
        }

        return Stub::make('Elastica\Mapping', $params);
    }

    /**
     * @param string $documentId
     * @param array $documentData
     * @param string $index
     * @param string $mappingType (@deprecated Will be removed once the support for Elasticsearch 6 and lower is dropped.)
     *
     * @return \Elastica\Document
     */
    protected function createDocument(string $documentId, array $documentData, string $index = '', string $mappingType = self::DEFAULT_MAPPING_TYPE): Document
    {
        if ($this->supportsMappingTypes()) {
            return new Document($documentId, $documentData, $mappingType);
        }

        return new Document($documentId, $documentData, $index);
    }

    /**
     * @deprecated Will be removed once the support for Elasticsearch 6 and lower is dropped.
     *
     * @return bool
     */
    public function supportsMappingTypes(): bool
    {
        return class_exists(Type::class);
    }
}
