<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\Index;
use Elastica\Response;
use Elastica\Type;
use Spryker\Client\Search\Exception\InvalidDataSetException;
use Spryker\Client\Search\Model\Elasticsearch\Writer\Writer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group SearchWriterTest
 * Add your own group annotations below this line
 */
class SearchWriterTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\Client
     */
    protected $client;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\Index
     */
    protected $index;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Elastica\Type
     */
    protected $type;

    /**
     * @return void
     */
    public function testWriteCreateDocumentsWithValidDataSet()
    {
        $dataSet = $this->getValidTestDataSet();
        $writer = $this->getElasticsearchWriter();
        $this->assertTrue($writer->write($dataSet));
    }

    /**
     * @expectedException \Spryker\Client\Search\Exception\InvalidDataSetException
     *
     * @return void
     */
    public function testWriteCreateDocumentsWithInvalidDataSet()
    {
        $dataSet = $this->getInvalidTestDataSet();
        $writer = $this->getElasticsearchWriter();
        $writer->write($dataSet);

        $this->expectException(InvalidDataSetException::class);
    }

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->type = $this->getMockType();
        $this->index = $this->getMockIndex();
        $this->client = $this->getMockClient();

        // now that index is setup, we can use it for mocking the Type class method getIndex
        $this->type->method('getIndex')->willReturn($this->index);
    }

    /**
     * Returns the valid data-set of array having non-numeric keys
     *
     * @return array
     */
    protected function getValidTestDataSet()
    {
        return [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
    }

    /**
     * Returns the invalid data-set of array having numeric keys
     *
     * @return array
     */
    protected function getInvalidTestDataSet()
    {
        return ['value1', 'value2'];
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Writer\Writer
     */
    protected function getElasticsearchWriter()
    {
        return new Writer($this->client, '', '');
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Client
     */
    protected function getMockClient()
    {
        $mockClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockClient->method('getIndex')->willReturn($this->index);

        return $mockClient;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Index
     */
    protected function getMockIndex()
    {
        $mockIndex = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIndex->method('getType')->willReturn($this->type);
        $mockIndex->method('refresh')->willReturn($this->getResponse());

        return $mockIndex;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Type
     */
    protected function getMockType()
    {
        $mockType = $this->getMockBuilder(Type::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockType->method('addDocuments')->willReturn(null);

        return $mockType;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Elastica\Response
     */
    protected function getResponse()
    {
        $mockResponse = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponse->method('isOk')->willReturn(true);

        return $mockResponse;
    }
}
