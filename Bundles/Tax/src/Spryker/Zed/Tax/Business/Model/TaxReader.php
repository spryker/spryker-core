<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\TaxRateCollectionTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface;
use Spryker\Zed\Tax\Persistence\TaxRepositoryInterface;

class TaxReader implements TaxReaderInterface
{
    /**
     * @var \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface
     */
    protected $taxRepository;

    /**
     * @param \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface $taxRepository
     */
    public function __construct(
        TaxQueryContainerInterface $queryContainer,
        TaxRepositoryInterface $taxRepository
    ) {
        $this->queryContainer = $queryContainer;
        $this->taxRepository = $taxRepository;
    }

    /**
     * @return \Generated\Shared\Transfer\TaxRateCollectionTransfer
     */
    public function getTaxRates()
    {
        $propelCollection = $this->queryContainer
            ->queryAllTaxRates()
            ->orderByName()
            ->find();

        $transferCollection = new TaxRateCollectionTransfer();
        foreach ($propelCollection as $taxRateEntity) {
            $taxRateTransfer = (new TaxRateTransfer())->fromArray($taxRateEntity->toArray());
            $transferCollection->addTaxRate($taxRateTransfer);
        }

        return $transferCollection;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function getTaxRate($id)
    {
        $taxRateEntity = $this->queryContainer
            ->queryTaxRate($id)
            ->findOne();

        if ($taxRateEntity === null) {
            throw new ResourceNotFoundException();
        }

        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->fromArray($taxRateEntity->toArray());

        if ($taxRateEntity->getCountry()) {
            $countryTransfer = new CountryTransfer();
            $countryTransfer->fromArray($taxRateEntity->getCountry()->toArray(), true);
            $taxRateTransfer->setCountry($countryTransfer);
        }

        return $taxRateTransfer;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function taxRateExists($id)
    {
        $taxRateQuery = $this->queryContainer->queryTaxRate($id);

        return $taxRateQuery->count() > 0;
    }

    /**
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets()
    {
        $propelCollection = $this->queryContainer->queryAllTaxSets()->find();

        $transferCollection = new TaxSetCollectionTransfer();
        foreach ($propelCollection as $taxSetEntity) {
            $taxSetTransfer = (new TaxSetTransfer())->fromArray($taxSetEntity->toArray());
            $transferCollection->addTaxSet($taxSetTransfer);
        }

        return $transferCollection;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function getTaxSet($id)
    {
        $taxSetEntity = $this->queryContainer
            ->queryTaxSet($id)
            ->findOne();

        if ($taxSetEntity === null) {
            throw new ResourceNotFoundException();
        }

        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->fromArray($taxSetEntity->toArray());
        foreach ($taxSetEntity->getSpyTaxRates() as $taxRateEntity) {
            $taxRateTransfer = new TaxRateTransfer();
            $taxRateTransfer->fromArray($taxRateEntity->toArray());

            if ($taxRateEntity->getCountry()) {
                $countryTransfer = new CountryTransfer();
                $countryTransfer->fromArray($taxRateEntity->getCountry()->toArray(), true);
                $taxRateTransfer->setCountry($countryTransfer);
            }

            $taxSetTransfer->addTaxRate($taxRateTransfer);
        }

        return $taxSetTransfer;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function taxSetExists($id)
    {
        $taxSetQuery = $this->queryContainer->queryTaxSet($id);

        return $taxSetQuery->count() > 0;
    }

    /**
     * @inheritdoc
     */
    public function taxSetWithSuchNameExists(string $name, ?int $idTaxSet = null): bool
    {
        return !$this->taxRepository->isTaxSetNameUnique($name, $idTaxSet);
    }
}
