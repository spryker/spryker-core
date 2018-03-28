<?php

namespace Spryker\Zed\Offer\Persistence;


use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

interface OfferRepositoryInterface
{
    /**
     * @param int $idOffer
     *
     * @return OfferTransfer
     */
    public function getOfferById(int $idOffer): OfferTransfer;
}