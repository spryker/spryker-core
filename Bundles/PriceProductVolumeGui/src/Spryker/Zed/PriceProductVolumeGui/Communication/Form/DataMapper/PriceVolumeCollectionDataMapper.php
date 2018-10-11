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
        $priceProductVolumeItemTransfers = $data[PriceVolumeCollectionFormType::FIELD_VOLUMES];
        $idCurrency = $data[PriceVolumeCollectionFormType::FIELD_ID_CURRENCY];
        $idStore = $data[PriceVolumeCollectionFormType::FIELD_ID_STORE];

        $priceProductVolumeItemArray = [];
        foreach ($priceProductVolumeItemTransfers as $priceProductVolumeItemTransfer) {
            $priceProductVolumeItemArray[] = array_filter($priceProductVolumeItemTransfer->toArray());
        }
        $priceProductVolumeItemArray = array_filter($priceProductVolumeItemArray);
        $priceData[$this->config->getVolumePriceTypeName()] = $priceProductVolumeItemArray;

        $priceDataJson = $this->utilEncodingService->encodeJson($priceData);

        $priceProductTransfer->getMoneyValue()->setPriceData($priceDataJson)
            ->setFkStore($idStore)
            ->setFkCurrency($idCurrency)
            ->setPriceDataChecksum($this->priceProductFacade->generatePriceDataChecksum($priceData));

        return $priceProductTransfer;
    }
}
