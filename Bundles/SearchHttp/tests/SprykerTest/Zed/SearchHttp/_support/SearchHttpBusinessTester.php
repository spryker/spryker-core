<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\SearchHttp;

use Codeception\Actor;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig;
use Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfigQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SearchHttpBusinessTester extends Actor
{
    use _generated\SearchHttpBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureSearchHttpConfigTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSearchHttpConfigQuery());
    }

    /**
     * @return \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfigQuery
     */
    protected function getSearchHttpConfigQuery(): SpySearchHttpConfigQuery
    {
        return SpySearchHttpConfigQuery::create();
    }

    /**
     * @param string $storeName
     *
     * @return \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig|null
     */
    public function findSearchHttpConfigByStoreName(string $storeName): ?SpySearchHttpConfig
    {
        return $this->getSearchHttpConfigQuery()
            ->filterByStoreName($storeName)
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     * @param \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig|null $searchHttpConfigEntity
     *
     * @return void
     */
    public function assertSearchHttpConfigStoredProperly(
        SearchHttpConfigTransfer $searchHttpConfigTransfer,
        ?SpySearchHttpConfig $searchHttpConfigEntity = null
    ): void {
        $this->assertNotNull($searchHttpConfigEntity);
        $this->assertNotEmpty($searchHttpConfigEntity->getData());
        $this->assertNotEmpty($searchHttpConfigEntity->getData()['search_http_configs']);
        $this->assertNotNull($searchHttpConfigEntity->getData()['search_http_configs'][0]['application_id']);
        $this->assertEquals(
            $searchHttpConfigTransfer->getApplicationId(),
            $searchHttpConfigEntity->getData()['search_http_configs'][0]['application_id'],
        );
        $this->assertNotNull($searchHttpConfigEntity->getData()['search_http_configs'][0]['url']);
        $this->assertEquals(
            $searchHttpConfigTransfer->getUrl(),
            $searchHttpConfigEntity->getData()['search_http_configs'][0]['url'],
        );
    }

    /**
     * @param string $storeReference
     * @param \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig|null $searchHttpConfigEntity
     *
     * @return void
     */
    public function assertSearchHttpConfigRemovedProperly(
        string $storeReference,
        ?SpySearchHttpConfig $searchHttpConfigEntity = null
    ): void {
        $this->assertNotNull($searchHttpConfigEntity);
        $this->assertNotEmpty($searchHttpConfigEntity->getData());
        $this->assertEquals([], $searchHttpConfigEntity->getData()['search_http_configs']);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createStoreWithStoreReference(): StoreTransfer
    {
        return (new StoreTransfer())
            ->setName('test_store_name')
            ->setStoreReference('test_store_reference');
    }
}
