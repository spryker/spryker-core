<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\PriceVolumeCollectionFormType;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Business\PriceProductVolumeGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 */
class PriceVolumeCollectionDataMapper implements PriceVolumeCollectionDataMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Service\PriceProductVolumeGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\PriceProductVolumeGui\PriceProductVolumeGuiConfig $config
     */
    public function __construct(
        PriceProductVolumeGuiToPriceProductFacadeInterface $priceProductFacade,
        PriceProductVolumeGuiToUtilEncodingServiceInterface $utilEncodingService,
        PriceProductVolumeGuiConfig $config
    ) {
        $this->priceProductFacade = $priceProductFacade;
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
            ->setFkCurrency($data[PriceVolumeCollectionFormType::FIELD_ID_CURRENCY])
            ->setPriceDataChecksum($this->priceProductFacade->generatePriceDataChecksum($priceData));

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

        if ($this->getPriceProductVolumeItemTransfers($data)) {
            $priceData[$this->config->getVolumePriceTypeName()] = $this->getPriceProductVolumeItemTransfers($data);
        }

        return $priceData;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeItemTransfer[]
     */
    protected function getPriceProductVolumeItemTransfers(array $data): array
    {
        $rawPriceProductVolumeItemTransfers = $data[PriceVolumeCollectionFormType::FIELD_VOLUMES];
        $nonFilteredPriceProductVolumeItemTransfers = [];

        $priceProductVolumeItemTransfers = [];
        foreach ($rawPriceProductVolumeItemTransfers as $priceProductVolumeItemTransfer) {
            $priceProductVolumeItemTransfers[] = array_filter($priceProductVolumeItemTransfer->toArray());
            $nonFilteredPriceProductVolumeItemTransfers[] = $priceProductVolumeItemTransfer->toArray();
        }
        $priceProductVolumeItemTransfers = array_filter($priceProductVolumeItemTransfers);

        return array_intersect_key($nonFilteredPriceProductVolumeItemTransfers, $priceProductVolumeItemTransfers);
    }
}
