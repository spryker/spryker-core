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

class MerchantKeyToIdMerchantProfileStep implements DataImportStepInterface
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
        $merchantKey = $dataSet[MerchantProfileAddressDataSetInterface::MERCHANT_KEY];

        if (!$merchantKey) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantProfileAddressDataSetInterface::MERCHANT_KEY));
        }

        if (!isset($this->idMerchantProfileCache[$merchantKey])) {
            $this->idMerchantProfileCache[$merchantKey] = $this->getIdMerchantProfile($merchantKey);
        }

        $dataSet[MerchantProfileAddressDataSetInterface::ID_MERCHANT_PROFILE] = $this->idMerchantProfileCache[$merchantKey];
    }

    /**
     * @param string $merchantKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdMerchantProfile(string $merchantKey): int
    {
        /** @var \Orm\Zed\Merchant\Persistence\SpyMerchantQuery $merchantQuery */
        $merchantQuery = SpyMerchantQuery::create()
            ->select(SpyMerchantProfileTableMap::COL_ID_MERCHANT_PROFILE);
        /** @var int $idMerchantProfile */
        $idMerchantProfile = $merchantQuery->innerJoinSpyMerchantProfile()
            ->findOneByMerchantKey($merchantKey);

        if (!$idMerchantProfile) {
            throw new EntityNotFoundException(sprintf('Could not find Merchant by key "%s"', $merchantKey));
        }

        return $idMerchantProfile;
    }
}
