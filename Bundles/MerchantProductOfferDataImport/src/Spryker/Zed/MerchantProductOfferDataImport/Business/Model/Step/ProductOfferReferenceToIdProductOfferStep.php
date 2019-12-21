<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class ProductOfferReferenceToIdProductOfferStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idProductOfferCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferReference = $dataSet[MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE];

        if (!$productOfferReference) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE));
        }

        if (!isset($this->idProductOfferCache[$productOfferReference])) {
            $this->idProductOfferCache[$productOfferReference] = $this->getIdProductOffer($productOfferReference);
        }

        $dataSet[MerchantProductOfferDataSetInterface::ID_PRODUCT_OFFER] = $this->idProductOfferCache[$productOfferReference];
    }

    /**
     * @param string $productOfferReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductOffer(string $productOfferReference): int
    {
        /** @var int $idProductOffer */
        $idProductOffer = SpyProductOfferQuery::create()
            ->select([SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER])
            ->findOneByProductOfferReference($productOfferReference);

        if (!$idProductOffer) {
            throw new EntityNotFoundException(sprintf('Could not find ProductOffer by reference "%s"', $productOfferReference));
        }

        return $idProductOffer;
    }
}
