<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;

class Remover implements RemoverInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $entityManager
     */
    public function __construct(PriceProductEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductStore(PriceProductTransfer $priceProductTransfer): void
    {
        $priceProductTransfer
            ->requireIdPriceProduct();

        $this->entityManager->deletePriceProduct($priceProductTransfer->getIdPriceProduct());

        $idPriceProductDefault = $priceProductTransfer->getPriceDimension()->getIdPriceProductDefault();

        $this->entityManager->deletePriceProductDefault($idPriceProductDefault);

        $this->getLogger()->warning(sprintf('Price for product with id "%s" was deleted', $priceProductTransfer->getIdPriceProduct()));
    }
}
