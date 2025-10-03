<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Persistence;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestPersistenceFactory getFactory()
 */
class MerchantRegistrationRequestRepository extends AbstractRepository implements MerchantRegistrationRequestRepositoryInterface
{
    /**
     * @module Country
     * @module Store
     */
    public function findMerchantRegistrationRequestById(int $idMerchantRegistrationRequest): ?MerchantRegistrationRequestTransfer
    {
        $merchantRegistrationRequestEntity = $this->getFactory()
            ->createSpyMerchantRegistrationRequestQuery()
            ->filterByIdMerchantRegistrationRequest($idMerchantRegistrationRequest)
            ->joinWithCountry()
            ->joinWithStore()
            ->findOne();

        if (!$merchantRegistrationRequestEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantRegistrationRequestMapper()
            ->mapMerchantRegistrationRequestEntityToMerchantRegistrationRequestTransfer(
                $merchantRegistrationRequestEntity,
                new MerchantRegistrationRequestTransfer(),
            );
    }

    public function isEmailAlreadyInUse(string $email): bool
    {
        $merchantRegistrationRequestQuery = $this->getFactory()
            ->createSpyMerchantRegistrationRequestQuery()
            ->filterByEmail($email)
            ->filterByStatus(MerchantRegistrationRequestConfig::STATUS_REJECTED, Criteria::ALT_NOT_EQUAL);

        $merchantQuery = $this->getFactory()
        ->getMerchantPropelQuery()
        ->filterByEmail($email);

        return $merchantRegistrationRequestQuery->exists() || $merchantQuery->exists();
    }

    public function isCompanyNameAlreadyInUse(string $companyName): bool
    {
        $merchantRegistrationRequestQuery = $this->getFactory()
            ->createSpyMerchantRegistrationRequestQuery()
            ->filterByCompanyName($companyName)
            ->filterByStatus(MerchantRegistrationRequestConfig::STATUS_REJECTED, Criteria::ALT_NOT_EQUAL);

        $merchantQuery = $this->getFactory()
            ->getMerchantPropelQuery()
            ->filterByName($companyName);

        return $merchantRegistrationRequestQuery->exists() || $merchantQuery->exists();
    }
}
