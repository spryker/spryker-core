<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GlossaryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\GlossaryStorage\Business\GlossaryStorageBusinessFactory;
use Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacade;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener\GlossaryKeyStorageListener;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Event\Listener\GlossaryTranslationStorageListener;
use SprykerTest\Zed\GlossaryStorage\GlossaryStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group GlossaryStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group GlossaryStorageListenerTest
 * Add your own group annotations below this line
 * @group QueueDependency
 */
class GlossaryStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\GlossaryStorage\GlossaryStorageCommunicationTester
     */
    protected $tester;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testGlossaryKeyStorageListenerStoreData(): void
    {
        // Prepare
        $idGlossaryKey = 1;
        $this->cleanUpGlossaryStorage($idGlossaryKey);
        $beforeCount = $this->createGlossaryStorageQuery()->filterByFkGlossaryKey($idGlossaryKey)->count();

        $glossaryKeyStorageListener = new GlossaryKeyStorageListener();
        $glossaryKeyStorageListener->setFacade($this->getGlossaryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idGlossaryKey),
        ];

        // Action
        $glossaryKeyStorageListener->handleBulk($eventTransfers, GlossaryEvents::GLOSSARY_KEY_PUBLISH);

        // Assert
        $this->assertGlossaryStorage($idGlossaryKey, $beforeCount);
    }

    /**
     * @return void
     */
    public function testGlossaryTranslationStorageListenerStoreData(): void
    {
        // Prepare
        $idGlossaryKey = 1;
        $this->cleanUpGlossaryStorage($idGlossaryKey);
        $beforeCount = $this->createGlossaryStorageQuery()->filterByFkGlossaryKey($idGlossaryKey)->count();

        $glossaryTranslationStorageListener = new GlossaryTranslationStorageListener();
        $glossaryTranslationStorageListener->setFacade($this->getGlossaryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY => $idGlossaryKey,
            ]),
        ];

        // Action
        $glossaryTranslationStorageListener->handleBulk($eventTransfers, GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE);

        // Assert
        $this->assertGlossaryStorage($idGlossaryKey, $beforeCount);
    }

    /**
     * @return void
     */
    public function testGlossaryTranslationStorageListenerDeletesDataForInactiveTranslations(): void
    {
        // Prepare
        $glossaryTranslationStorageListener = new GlossaryTranslationStorageListener();
        $glossaryTranslationStorageListener->setFacade($this->getGlossaryStorageFacade());
        $idGlossaryKey = $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => 'test-key',
            KeyTranslationTransfer::LOCALES => [
                'en_US' => 'English',
                'de_DE' => 'Deutsch',
            ],
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY => $idGlossaryKey,
            ]),
        ];

        $glossaryTranslationStorageListener->handleBulk($eventTransfers, GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE);

        $beforeCount = $this->createGlossaryStorageQuery()->filterByFkGlossaryKey($idGlossaryKey)->count();

        // Action
        $this->createGlossaryTranslationQuery()
            ->filterByFkGlossaryKey($idGlossaryKey)
            ->findOne()
            ->setIsActive(false)
            ->save();

        $glossaryTranslationStorageListener->handleBulk($eventTransfers, GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE);

        // Assert
        $this->assertGlossaryStorageCount($idGlossaryKey, $beforeCount - 1);
    }

    /**
     * @return void
     */
    public function testGlossaryTranslationStorageListenerDeletesDataForEmptyTranslations(): void
    {
        // Prepare
        $glossaryTranslationStorageListener = new GlossaryTranslationStorageListener();
        $glossaryTranslationStorageListener->setFacade($this->getGlossaryStorageFacade());
        $idGlossaryKey = $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => 'test-key',
            KeyTranslationTransfer::LOCALES => [
                'en_US' => 'English',
                'de_DE' => 'Deutsch',
            ],
        ]);

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY => $idGlossaryKey,
            ]),
        ];

        $glossaryTranslationStorageListener->handleBulk($eventTransfers, GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE);

        $beforeCount = $this->createGlossaryStorageQuery()->filterByFkGlossaryKey($idGlossaryKey)->count();

        // Action
        $this->tester->haveTranslation([
            KeyTranslationTransfer::GLOSSARY_KEY => 'test-key',
            KeyTranslationTransfer::LOCALES => [
                'en_US' => '',
                'de_DE' => 'Deutsch',
            ],
        ]);

        $glossaryTranslationStorageListener->handleBulk($eventTransfers, GlossaryEvents::ENTITY_SPY_GLOSSARY_KEY_UPDATE);

        // Assert
        $this->assertGlossaryStorageCount($idGlossaryKey, $beforeCount - 1);
    }

    /**
     * @param int $idGlossaryKey
     *
     * @return void
     */
    protected function cleanUpGlossaryStorage(int $idGlossaryKey): void
    {
        SpyGlossaryStorageQuery::create()->filterByFkGlossaryKey($idGlossaryKey)->delete();
    }

    /**
     * @param int $idGlossaryKey
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertGlossaryStorage(int $idGlossaryKey, int $beforeCount): void
    {
        $this->assertGlossaryStorageCount($idGlossaryKey, $beforeCount + 2);
        $spyGlossaryStorage = $this->createGlossaryStorageQuery()
            ->orderByFkGlossaryKey()
            ->filterByLocale('en_US')
            ->findOneByFkGlossaryKey($idGlossaryKey);
        $this->assertNotNull($spyGlossaryStorage);
        $this->assertSame('cart.remove.items.success', $spyGlossaryStorage->getData()['GlossaryKey']['key']);
        $this->assertSame('Products were removed successfully', $spyGlossaryStorage->getData()['value']);
    }

    /**
     * @param int $idGlossaryKey
     * @param int $expectedCount
     *
     * @return void
     */
    protected function assertGlossaryStorageCount(int $idGlossaryKey, int $expectedCount): void
    {
        $glossaryStorageCount = $this->createGlossaryStorageQuery()->filterByFkGlossaryKey($idGlossaryKey)->count();
        $this->assertGreaterThan(0, $glossaryStorageCount);
        $this->assertEquals($expectedCount, $glossaryStorageCount);
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacade
     */
    protected function getGlossaryStorageFacade(): GlossaryStorageFacade
    {
        $factory = new GlossaryStorageBusinessFactory();
        $factory->setConfig(new GlossaryStorageConfigMock());

        $facade = new GlossaryStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryTranslationQuery
     */
    protected function createGlossaryTranslationQuery(): SpyGlossaryTranslationQuery
    {
        return SpyGlossaryTranslationQuery::create()::create();
    }

    /**
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery
     */
    protected function createGlossaryStorageQuery(): SpyGlossaryStorageQuery
    {
        return SpyGlossaryStorageQuery::create();
    }
}
