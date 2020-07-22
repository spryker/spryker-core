<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantProductOfferGui\Persistence\MerchantProductOfferGuiRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MerchantProductOfferTableExpander implements MerchantProductOfferTableExpanderInterface
{
    protected const COL_POSITION = 2;

    protected const URL_PARAM_ID_MERCHANT = 'id-merchant';
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
            (new MerchantProductOfferCriteriaFilterTransfer())->setIdMerchant($idMerchant)
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
        $header = $this->insertArray($header, [static::COL_MERCHANT_NAME]);
        $config->setHeader($header);

        return $config;
    }

    /**
     * @param array $data
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $data, array $item): array
    {
        return $this->insertArray($data, [$item[MerchantTransfer::NAME]]);
    }

    /**
     * @param array $data
     * @param array $dataPart
     *
     * @return array
     */
    protected function insertArray(array $data, array $dataPart)
    {
        return array_merge(array_splice($data, 0, static::COL_POSITION), $dataPart, array_splice($data, 0));
    }
}
