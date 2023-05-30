<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ServicePoint;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ServiceBuilder;
use Generated\Shared\DataBuilder\ServicePointAddressBuilder;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ServicePointBusinessTester extends Actor
{
    use _generated\ServicePointBusinessTesterActions;

    /**
     * @var string
     */
    protected const COUNTRY_ISO2_CODE = '00';

    /**
     * @var string
     */
    protected const COUNTRY_ISO3_CODE = '000';

    /**
     * @return void
     */
    public function ensureServicePointTablesAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getServicePointQuery(),
        );

        $this->ensureDatabaseTableIsEmpty(
            $this->getServiceTypeQuery(),
        );

        $this->ensureDatabaseTableIsEmpty(
            $this->getServiceQuery(),
        );
    }

    /**
     * @param string $storeName
     * @param array $servicePointSeedData
     * @param bool $isStoreCreationNeeded
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointTransferWithStoreRelation(
        string $storeName,
        array $servicePointSeedData = [],
        bool $isStoreCreationNeeded = true
    ): ServicePointTransfer {
        $storeTransfer = (new StoreTransfer())->setName($storeName);

        if ($isStoreCreationNeeded) {
            $storeTransfer = $this->haveStore($storeTransfer->toArray());
        }

        $servicePointTransfer = (new ServicePointBuilder($servicePointSeedData))
            ->withStoreRelation([
                StoreRelationTransfer::STORES => [
                    [
                        StoreTransfer::ID_STORE => $storeTransfer->getIdStore(),
                        StoreTransfer::NAME => $storeTransfer->getNameOrFail(),
                    ],
                ],
            ])->build();

        return $servicePointTransfer;
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function createServicePointAddressTransferWithRelations(array $seed = []): ServicePointAddressTransfer
    {
        $servicePointAddressTransfer = (new ServicePointAddressBuilder($seed))->build();

        $countryTransfer = $this->haveCountryTransfer([
            CountryTransfer::ISO2_CODE => static::COUNTRY_ISO2_CODE,
            CountryTransfer::ISO3_CODE => static::COUNTRY_ISO3_CODE,
        ]);

        if (!$servicePointAddressTransfer->getServicePoint()) {
            $storeTransfer = $this->haveStore();
            $servicePointTransfer = $this->createServicePointTransferWithStoreRelation($storeTransfer->getName());
            $servicePointTransfer = $this->haveServicePoint($servicePointTransfer->toArray());
            $servicePointAddressTransfer->setServicePoint($servicePointTransfer);
        }

        if (!$servicePointAddressTransfer->getCountry()) {
            $servicePointAddressTransfer->setCountry($countryTransfer);
        }

        if (!$servicePointAddressTransfer->getRegion()) {
            $regionTransfer = $this->haveRegion([
                RegionTransfer::FK_COUNTRY => $countryTransfer->getIdCountry(),
            ]);
            $servicePointAddressTransfer->setRegion($regionTransfer);
        }

        return $servicePointAddressTransfer;
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointAddressQuery
     */
    public function getServicePointAddressQuery(): SpyServicePointAddressQuery
    {
        return SpyServicePointAddressQuery::create();
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function createServiceTransferWithRelations(array $seed = []): ServiceTransfer
    {
        $servicePointTransfer = $this->haveServicePoint($seed[ServiceTransfer::SERVICE_POINT] ?? [])->toArray();
        $serviceTypeTransfer = $this->haveServiceType($seed[ServiceTransfer::SERVICE_TYPE] ?? [])->toArray();

        return (new ServiceBuilder($seed))
            ->withServicePoint($servicePointTransfer)
            ->withServiceType($serviceTypeTransfer)
            ->build();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    public function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    public function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceQuery
     */
    public function getServiceQuery(): SpyServiceQuery
    {
        return SpyServiceQuery::create();
    }
}
