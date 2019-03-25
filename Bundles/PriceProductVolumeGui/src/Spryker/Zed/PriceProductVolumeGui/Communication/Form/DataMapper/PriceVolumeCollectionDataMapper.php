<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\PriceVolumeCollectionFormType;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 */
class PriceVolumeCollectionDataMapper implements PriceVolumeCollectionDataMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig $config
     */
    public function __construct(
        PriceProductVolumeGuiToUtilEncodingServiceInterface $utilEncodingService,
        PriceProductVolumeGuiConfig $config
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->config = $config;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapArrayToPriceProductTransfer(array $data, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceData = $this->getPriceData($data);

        $priceProductTransfer->getMoneyValue()
            ->setPriceData($this->utilEncodingService->encodeJson($priceData))
            ->setFkStore($data[PriceVolumeCollectionFormType::FIELD_ID_STORE])
            ->setFkCurrency($data[PriceVolumeCollectionFormType::FIELD_ID_CURRENCY]);

        return $priceProductTransfer;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getPriceData(array $data): array
    {
        $priceData = [];
        $priceProductVolumeItemArray = $this->getPriceProductVolumeItemArray($data);

        if ($priceProductVolumeItemArray) {
            $priceData[$this->config->getVolumePriceTypeName()] = $priceProductVolumeItemArray;
        }

        return $priceData;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeItemTransfer[]
     */
    protected function getPriceProductVolumeItemArray(array $data): array
    {
        if (!isset($data[PriceVolumeCollectionFormType::FIELD_VOLUMES])) {
            return [];
        }

        $rawPriceProductVolumeItemTransfers = $data[PriceVolumeCollectionFormType::FIELD_VOLUMES];
        $priceProductVolumeItemArray = [];
        $nonFilteredPriceProductVolumeItemArray = [];

        foreach ($rawPriceProductVolumeItemTransfers as $priceProductVolumeItemTransfer) {
            $priceProductVolumeItemArray[] = array_filter($priceProductVolumeItemTransfer->toArray());
            $nonFilteredPriceProductVolumeItemArray[] = $priceProductVolumeItemTransfer->toArray();
        }
        $priceProductVolumeItemArray = array_filter($priceProductVolumeItemArray);

        return array_intersect_key($nonFilteredPriceProductVolumeItemArray, $priceProductVolumeItemArray);
    }
}
