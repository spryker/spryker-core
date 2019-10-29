<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SearchElasticsearch\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Request;
use Elastica\Snapshot;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig;
use SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper;

class ElasticsearchHelper extends Module
{
    protected const INDEX_SUFFIX = '_testing';
    protected const REPOSITORY_LOCATION_FILE_NAME = 'search_test_file';

    /**
     * @var array
     */
    protected $cleanup = [];

    /**
     * @param string $indexName
     *
     * @return \Elastica\Index
     */
    public function haveIndex(string $indexName): Index
    {
        $indexName .= static::INDEX_SUFFIX;
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
            new Document($documentId, $documentData, '_doc'),
        ];

        $index->addDocuments($documents);
        $index->flush();

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
        return new Client($this->getConfig()->getClientConfig());
    }

    /**
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return void
     */
    public function haveRepository(string $repositoryName, $type = 'fs', array $settings = []): void
    {
        $settings['location'] = $this->getVirtualRepositoryLocation();
        $snapshot = $this->createElasticaSnapshot();
        $snapshot->registerRepository($repositoryName, $type, $settings);
    }

    /**
     * @return \Elastica\Snapshot
     */
    protected function createElasticaSnapshot(): Snapshot
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
}
