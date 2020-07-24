<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search;

use Codeception\Test\Unit;
use Elastica\Index;
use Generated\Shared\Transfer\SearchDocumentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group SearchClientBCTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Client\Search\SearchClientTester $tester
 */
class SearchClientBCTest extends Unit
{
    protected const INDEX_NAME = 'de_search_devtest';
    protected const MAPPING_TYPE = 'page';

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        $this->skipIfElasticsearch7();

        parent::_setUp();

        $this->setupEnvironmentForBCSearchTesting();
    }

    /**
     * @return void
     */
    public function testCanWriteDocument(): void
    {
        // Arrange
        $documentId = 'document-id';
        $documentData = ['foo' => 'bar'];
        $dataSet = [
            $documentId => $documentData,
        ];
        $this->tester->haveIndex(static::INDEX_NAME);

        // Act
        $this->tester->getClient()->write($dataSet);

        // Assert
        $this->tester->assertDocumentExists($documentId, static::INDEX_NAME, $documentData);
    }

    /**
     * @return void
     */
    public function testCanWriteMultipleDocuments(): void
    {
        // Arrange
        $documentId = 'document-id';
        $documentData = ['foo' => 'bar'];
        $anotherDocumentId = 'another-document-id';
        $anotherDocumentData = ['bar' => 'baz'];

        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId, static::INDEX_NAME, $documentData);
        $anotherSearchDocumentTransfer = $this->createSearchDocumentTransfer($anotherDocumentId, static::INDEX_NAME, $anotherDocumentData);

        // Act
        $this->tester->getClient()->writeBulk([$searchDocumentTransfer, $anotherSearchDocumentTransfer]);

        // Assert
        foreach ([$searchDocumentTransfer, $anotherSearchDocumentTransfer] as $currentSearchDocumentTransfer) {
            $this->tester->assertDocumentExists($currentSearchDocumentTransfer->getId(), static::INDEX_NAME, $currentSearchDocumentTransfer->getData());
        }
    }

    /**
     * @return void
     */
    public function testCanReadDocument(): void
    {
        $documentId = 'document-id';
        $documentData = ['foo' => 'bar'];
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId, $documentData);

        $result = $this->tester->getClient()->read($documentId);

        $this->assertSame($documentData, $result->getData());
    }

    /**
     * @return void
     */
    public function testCanDeleteDocument(): void
    {
        $documentId = 'document-id';
        $dataSet = [
            $documentId => [],
        ];
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId);

        $this->tester->getClient()->delete($dataSet);

        $this->tester->assertDocumentDoesNotExist($documentId, static::INDEX_NAME);
    }

    /**
     * @return void
     */
    public function testCanDeleteMultipleDocuments(): void
    {
        // Arrange
        $documentId = 'document-id';
        $anotherDocumentId = 'another-document-id';

        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId);
        $anotherSearchDocumentTransfer = $this->createSearchDocumentTransfer($anotherDocumentId);

        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $documentId);
        $this->tester->haveDocumentInIndex(static::INDEX_NAME, $anotherDocumentId);

        // Act
        $this->tester->getClient()->deleteBulk([$searchDocumentTransfer, $anotherSearchDocumentTransfer]);

        // Assert
        foreach ([$documentId, $anotherDocumentId] as $currentDocumentId) {
            $this->tester->assertDocumentDoesNotExist($currentDocumentId, static::INDEX_NAME);
        }
    }

    /**
     * @param string $documentId
     * @param string $indexName
     * @param array|string|null $documentData
     * @param string $typeName
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    protected function createSearchDocumentTransfer(
        string $documentId,
        string $indexName = self::INDEX_NAME,
        $documentData = null,
        string $typeName = self::MAPPING_TYPE
    ): SearchDocumentTransfer {
        $searchDocumentTransfer = (new SearchDocumentTransfer())->setId($documentId)
            ->setIndex($indexName)
            ->setType($typeName);

        if ($documentData) {
            $searchDocumentTransfer->setData($documentData);
        }

        return $searchDocumentTransfer;
    }

    /**
     * @return void
     */
    protected function setupEnvironmentForBCSearchTesting(): void
    {
        $this->tester->mockConfigMethod('getSearchIndexName', static::INDEX_NAME);
        $this->tester->mockConfigMethod('getSearchDocumentType', static::MAPPING_TYPE);
    }

    /**
     * @return void
     */
    protected function skipIfElasticsearch7(): void
    {
        if (!method_exists(Index::class, 'getType')) {
            $this->markTestSkipped('This test is not suitable for Elasticsearch 7 or higher');
        }
    }
}
