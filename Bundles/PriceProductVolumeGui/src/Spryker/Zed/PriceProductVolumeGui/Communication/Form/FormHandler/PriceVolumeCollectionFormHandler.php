<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form\FormHandler;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper\PriceVolumeCollectionDataMapperInterface;
use Spryker\Zed\PriceProductVolumeGui\Communication\Form\PriceVolumeCollectionFormType;
use Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface;

class PriceVolumeCollectionFormHandler
{
    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper\PriceVolumeCollectionDataMapperInterface
     */
    protected $priceVolumeCollectionDataMapper;

    /**
     * @param \Spryker\Zed\PriceProductVolumeGui\Dependency\Facade\PriceProductVolumeGuiToPriceProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper\PriceVolumeCollectionDataMapperInterface $priceVolumeCollectionDataMapper
     */
    public function __construct(
        PriceProductVolumeGuiToPriceProductFacadeInterface $productFacade,
        PriceVolumeCollectionDataMapperInterface $priceVolumeCollectionDataMapper
    ) {
        $this->productFacade = $productFacade;
        $this->priceVolumeCollectionDataMapper = $priceVolumeCollectionDataMapper;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProduct(array $data, PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer = $this->priceVolumeCollectionDataMapper
            ->mapArrayToPriceProductTransfer(
                $data,
                $priceProductTransfer
            );

        if ($data[PriceVolumeCollectionFormType::FIELD_ID_PRODUCT_CONCRETE] && !$priceProductTransfer->getIdProduct()) {
            $priceProductTransfer->setIdPriceProduct(null);
            $priceProductTransfer->setIdProduct($data[PriceVolumeCollectionFormType::FIELD_ID_PRODUCT_CONCRETE]);
        }

        return $this->productFacade->persistPriceProductStore($priceProductTransfer);
    }
}
