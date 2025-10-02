<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form\DataTransformer;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>|null, list<int>>
 */
class AssigneeCompanyBusinessUnitsDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>|mixed $value
     *
     * @return list<int>
     */
    public function transform(mixed $value): array
    {
        $companyBusinessUnitIds = [];

        foreach ($value as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return $companyBusinessUnitIds;
    }

    /**
     * @param list<int>|mixed|null $value
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>
     */
    public function reverseTransform(mixed $value): ArrayObject
    {
        $companyBusinessUnitTransfers = new ArrayObject();

        foreach ($value as $idCompanyBusinessUnit) {
            $companyBusinessUnitTransfers->append(
                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnit),
            );
        }

        return $companyBusinessUnitTransfers;
    }
}
