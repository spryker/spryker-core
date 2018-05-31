<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GlossaryStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery;
use PHPUnit\Framework\SkippedTestError;
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
 */
class GlossaryStorageListenerTest extends Unit
{
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
    }

    /**
     * @return void
     */
    public function testGlossaryKeyStorageListenerStoreData()
    {
        SpyGlossaryStorageQuery::create()->filterByFkGlossaryKey(1)->delete();
        $beforeCount = SpyGlossaryStorageQuery::create()->count();

        $glossaryKeyStorageListener = new GlossaryKeyStorageListener();
        $glossaryKeyStorageListener->setFacade($this->getGlossaryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $glossaryKeyStorageListener->handleBulk($eventTransfers, GlossaryEvents::GLOSSARY_KEY_PUBLISH);

        // Assert
        $this->assertGlossaryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testGlossaryTranslationStorageListenerStoreData()
    {
        SpyGlossaryStorageQuery::create()->filterByFkGlossaryKey(1)->delete();
        $beforeCount = SpyGlossaryStorageQuery::create()->count();

        $glossaryTranslationStorageListener = new GlossaryTranslationStorageListener();
        $glossaryTranslationStorageListener->setFacade($this->getGlossaryStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY => 1,
            ]),
        ];
        $glossaryTranslationStorageListener->handleBulk($eventTransfers, GlossaryEvents::ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE);

        // Assert
        $this->assertGlossaryStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacade
     */
    protected function getGlossaryStorageFacade()
    {
        $factory = new GlossaryStorageBusinessFactory();
        $factory->setConfig(new GlossaryStorageConfigMock());

        $facade = new GlossaryStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertGlossaryStorage($beforeCount)
    {
        $glossaryStorageCount = SpyGlossaryStorageQuery::create()->count();
        $this->assertSame($beforeCount + 2, $glossaryStorageCount);
        $spyGlossaryStorage = SpyGlossaryStorageQuery::create()
            ->orderByFkGlossaryKey()
            ->findOneByFkGlossaryKey(1);
        $this->assertNotNull($spyGlossaryStorage);
        $data = $spyGlossaryStorage->getData();
        $this->assertSame('cart.remove.items.success', $data['GlossaryKey']['key']);
        $this->assertSame('Products were removed successfully', $data['value']);
    }
}
