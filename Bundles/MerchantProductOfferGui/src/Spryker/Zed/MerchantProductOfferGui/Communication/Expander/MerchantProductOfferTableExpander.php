<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantProductOfferGui\Persistence\MerchantProductOfferGuiRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MerchantProductOfferTableExpander implements MerchantProductOfferTableExpanderInterface
{
    /**
     * @var string
     */
    protected const URL_PARAM_ID_MERCHANT = 'id-merchant';

    /**
     * @var string
     */
    protected const COL_MERCHANT_NAME = 'Merchant';

    /**
     * @var \Spryker\Zed\MerchantProductOfferGui\Persistence\MerchantProductOfferGuiRepositoryInterface
     */
    protected $merchantProductOfferGuiRepository;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Spryker\Zed\MerchantProductOfferGui\Persistence\MerchantProductOfferGuiRepositoryInterface $merchantProductOfferGuiRepository
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(
        MerchantProductOfferGuiRepositoryInterface $merchantProductOfferGuiRepository,
        Request $request
    ) {
        $this->merchantProductOfferGuiRepository = $merchantProductOfferGuiRepository;
        $this->request = $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        $idMerchant = $this->request->get(static::URL_PARAM_ID_MERCHANT);

        return $this->merchantProductOfferGuiRepository->expandQueryCriteriaTransfer(
            $queryCriteriaTransfer,
            (new MerchantProductOfferCriteriaTransfer())->setIdMerchant($idMerchant),
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandTableConfiguration(TableConfiguration $config): TableConfiguration
    {
        $header = $config->getHeader();
        $header = $this->insertAfterHeader(
            $header,
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
            [MerchantTransfer::NAME => static::COL_MERCHANT_NAME],
        );
        $config->setHeader($header);

        $sortable = $config->getSortable();

        $sortable[] = MerchantTransfer::NAME;

        $config->setSortable($sortable);

        return $config;
    }

    /**
     * @phpstan-param array<string, mixed> $rowData
     * @phpstan-param array<string, mixed> $productOfferData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $rowData
     * @param array $productOfferData
     *
     * @return array
     */
    public function expandData(array $rowData, array $productOfferData): array
    {
        return $this->insertAfterHeader(
            $rowData,
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
            [MerchantTransfer::NAME => $productOfferData[MerchantTransfer::NAME]],
        );
    }

    /**
     * @phpstan-param array<string, mixed> $rowData
     * @phpstan-param array<string, mixed> $extensionData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $rowData
     * @param string $key
     * @param array $extensionData
     *
     * @return array
     */
    protected function insertAfterHeader(array $rowData, string $key, array $extensionData): array
    {
        $keys = array_keys($rowData);
        $index = array_search($key, $keys);
        $pos = $index === false ? count($rowData) : $index + 1;

        return array_merge(array_slice($rowData, 0, $pos), $extensionData, array_slice($rowData, $pos));
    }
}
