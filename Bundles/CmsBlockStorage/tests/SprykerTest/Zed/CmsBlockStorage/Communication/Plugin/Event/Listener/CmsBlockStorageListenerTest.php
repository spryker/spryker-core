<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\CmsBlock\Dependency\CmsBlockEvents;
use Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageBusinessFactory;
use Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacade;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockGlossaryKeyMappingBlockStorageListener;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener\CmsBlockStorageListener;
use SprykerTest\Zed\CmsBlockStorage\CmsBlockStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CmsBlockStorageListenerTest
 * Add your own group annotations below this line
 */
class CmsBlockStorageListenerTest extends Unit
{
    const NUMBER_OF_LOCALES = 2;
    const NUMBER_OF_STORES = 3;

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
    public function testCmsBlockStorageListenerStoreData()
    {
        SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->delete();
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockStorageListener = new CmsBlockStorageListener();
        $cmsBlockStorageListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $cmsBlockStorageListener->handleBulk($eventTransfers, CmsBlockEvents::CMS_BLOCK_PUBLISH);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testCmsBlockGlossaryKeyMappingBlockStorageListenerStoreData()
    {
        SpyCmsBlockStorageQuery::create()->filterByFkCmsBlock(1)->delete();
        $beforeCount = SpyCmsBlockStorageQuery::create()->count();

        $cmsBlockGlossaryKeyMappingBlockStorageListener = new CmsBlockGlossaryKeyMappingBlockStorageListener();
        $cmsBlockGlossaryKeyMappingBlockStorageListener->setFacade($this->getCmsBlockStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK => 1,
            ]),
        ];
        $cmsBlockGlossaryKeyMappingBlockStorageListener->handleBulk($eventTransfers, CmsBlockEvents::ENTITY_SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPING_CREATE);

        // Assert
        $this->assertCmsBlockCategoryStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacade
     */
    protected function getCmsBlockStorageFacade()
    {
        $factory = new CmsBlockStorageBusinessFactory();
        $factory->setConfig(new CmsBlockStorageConfigMock());

        $facade = new CmsBlockStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertCmsBlockCategoryStorage($beforeCount)
    {
        $count = SpyCmsBlockStorageQuery::create()->count();
        $this->assertSame($beforeCount + static::NUMBER_OF_LOCALES * static::NUMBER_OF_STORES, $count);
        $cmsBlockStorage = SpyCmsBlockStorageQuery::create()->filterByLocale('en_US')->orderByIdCmsBlockStorage()->findOneByFkCmsBlock(1);
        $this->assertNotNull($cmsBlockStorage);
        $data = $cmsBlockStorage->getData();
        $this->assertSame('Teaser for home page', $data['name']);
    }
}
