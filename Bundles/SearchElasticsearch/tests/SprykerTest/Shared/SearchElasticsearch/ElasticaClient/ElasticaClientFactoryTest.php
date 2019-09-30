<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SearchElasticsearch\ElasticaClient;

use Codeception\Test\Unit;
use Elastica\Client;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Spryker\Shared\SearchElasticsearch\ElasticaClient\ElasticaClientFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group SearchElasticsearch
 * @group ElasticaClient
 * @group ElasticaClientFactoryTest
 * Add your own group annotations below this line
 */
class ElasticaClientFactoryTest extends Unit
{
    /**
     * @return void
     */
    protected function _setUp()
    {
        parent::_setUp();

        $this->setUpClientMock();
    }

    /**
     * @return void
     */
    public function testElasticaClientIsNotRecreated(): void
    {
        $elasticClientFactory = new ElasticaClientFactory();
        $client = $elasticClientFactory->createClient([]);

        $this->assertInstanceOf(MockObject::class, $client);
    }

    /**
     * @return void
     */
    protected function setUpClientMock(): void
    {
        $factoryReflectionClass = new ReflectionClass(ElasticaClientFactory::class);
        $clientProperty = $factoryReflectionClass->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue(
            $this->createMock(Client::class)
        );
    }
}
