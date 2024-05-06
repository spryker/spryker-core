<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence\Mapper;

use Generated\Shared\Transfer\TaxAppApiUrlsTransfer;
use Generated\Shared\Transfer\TaxAppConfigCollectionTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig;
use Propel\Runtime\Collection\Collection;
use Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface;

class TaxAppConfigMapper
{
    /**
     * @var \Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface
     */
    protected TaxAppToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(TaxAppToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig $taxAppConfigEntity
     *
     * @return \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig
     */
    public function mapTaxAppConfigTransferToTaxAppConfigEntity(
        TaxAppConfigTransfer $taxAppConfigTransfer,
        SpyTaxAppConfig $taxAppConfigEntity
    ): SpyTaxAppConfig {
        $taxAppApiUrlsJson = $this->utilEncodingService->encodeJson($taxAppConfigTransfer->getApiUrlsOrFail()->toArray());
        $taxAppConfigTransfer = $taxAppConfigTransfer->toArray();
        unset($taxAppConfigTransfer['api_urls']);

        $taxAppConfigEntity = $taxAppConfigEntity->fromArray($taxAppConfigTransfer);
        $taxAppConfigEntity->setApiUrls($taxAppApiUrlsJson ?? '');

        return $taxAppConfigEntity;
    }

    /**
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig $spyTaxAppConfigTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigTransfer
     */
    public function mapTaxAppConfigEntityToTaxAppConfigTransfer(
        SpyTaxAppConfig $spyTaxAppConfigTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer
    ): TaxAppConfigTransfer {
        $taxAppApiUrlsArray = $this->utilEncodingService->decodeJson($spyTaxAppConfigTransfer->getApiUrls(), true);
        $taxAppApiUrlsTransfer = (new TaxAppApiUrlsTransfer())->fromArray((array)($taxAppApiUrlsArray ?? []), true);

        $spyTaxAppConfigTransfer = $spyTaxAppConfigTransfer->toArray();
        unset($spyTaxAppConfigTransfer['api_urls']);

        $taxAppConfigTransfer = $taxAppConfigTransfer->fromArray($spyTaxAppConfigTransfer, true);
        $taxAppConfigTransfer->setApiUrls($taxAppApiUrlsTransfer);

        return $taxAppConfigTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $taxAppConfigEntities
     * @param \Generated\Shared\Transfer\TaxAppConfigCollectionTransfer $taxAppConfigCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigCollectionTransfer
     */
    public function mapTaxAppConfigEntitiesToTaxAppConfigCollectionTransfer(
        Collection $taxAppConfigEntities,
        TaxAppConfigCollectionTransfer $taxAppConfigCollectionTransfer
    ): TaxAppConfigCollectionTransfer {
        foreach ($taxAppConfigEntities as $taxAppConfigEntity) {
            $taxAppConfigCollectionTransfer->addTaxAppConfig(
                $this->mapTaxAppConfigEntityToTaxAppConfigTransfer($taxAppConfigEntity, new TaxAppConfigTransfer()),
            );
        }

        return $taxAppConfigCollectionTransfer;
    }
}
