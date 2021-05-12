<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Address\Step;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\MerchantProfile\Persistence\Map\SpyMerchantProfileTableMap;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProfileDataImport\Business\Address\DataSet\MerchantProfileAddressDataSetInterface;

class MerchantReferenceToIdMerchantProfileStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idMerchantProfileCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantReference = $dataSet[MerchantProfileAddressDataSetInterface::MERCHANT_REFERENCE];

        if (!$merchantReference) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantProfileAddressDataSetInterface::MERCHANT_REFERENCE));
        }

        if (!isset($this->idMerchantProfileCache[$merchantReference])) {
            $this->idMerchantProfileCache[$merchantReference] = $this->getIdMerchantProfile($merchantReference);
        }

        $dataSet[MerchantProfileAddressDataSetInterface::ID_MERCHANT_PROFILE] = $this->idMerchantProfileCache[$merchantReference];
    }

    /**
     * @param string $merchantReference
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchantProfile(string $merchantReference): int
    {
        /** @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery */
        $merchantQuery = SpyMerchantQuery::create()
            ->select(SpyMerchantProfileTableMap::COL_ID_MERCHANT_PROFILE);
        /** @var int $idMerchantProfile */
        $idMerchantProfile = $merchantQuery->innerJoinSpyMerchantProfile()
            ->findOneByMerchantReference($merchantReference);

        if (!$idMerchantProfile) {
            throw new EntityNotFoundException(sprintf('Could not find Merchant by reference "%s"', $merchantReference));
        }

        return $idMerchantProfile;
    }
}
