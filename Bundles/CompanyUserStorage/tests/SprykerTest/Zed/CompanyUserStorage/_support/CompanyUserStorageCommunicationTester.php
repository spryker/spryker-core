<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserStorage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\CompanyUserStorage\Business\CompanyUserStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyUserStorageCommunicationTester extends Actor
{
    use _generated\CompanyUserStorageCommunicationTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->setDependency(StoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
    }

    /**
     * @param bool $isActiveCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUserTransfer(bool $isActiveCompany = true): CompanyUserTransfer
    {
        $companyTransfer = $this->haveCompany([
            CompanyTransfer::IS_ACTIVE => $isActiveCompany,
            CompanyTransfer::STATUS => SpyCompanyTableMap::COL_STATUS_APPROVED,
        ]);
        $customerTransfer = $this->haveCustomer();

        return $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY => $companyTransfer,
            CompanyUserTransfer::IS_ACTIVE => true,
        ]);
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        $storeStorageStoreExpanderPluginMock = Stub::makeEmpty(StoreExpanderPluginInterface::class, [
            'expand' => $storeTransfer,
        ]);

        return $storeStorageStoreExpanderPluginMock;
    }
}
