<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel;

use ArrayObject;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper\CompanyUnitAddressMapperInterface;

class CompanyUnitAddressHydrator implements CompanyUnitAddressHydratorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper\CompanyUnitAddressMapperInterface
     */
    protected $companyUnitAddressMapper;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper\CompanyUnitAddressMapperInterface $companyUnitAddressMapper
     */
    public function __construct(CompanyUnitAddressMapperInterface $companyUnitAddressMapper)
    {
        $this->companyUnitAddressMapper = $companyUnitAddressMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $companyUnitAddressCollection
     *
     * @return \ArrayObject
     */
    public function hydrateUnitAddressCollectionEntityCollection(
        ObjectCollection $companyUnitAddressCollection
    ): ArrayObject {
        $companyUnitAddresses = new ArrayObject();
        foreach ($companyUnitAddressCollection as $companyUnitAddressEntity) {
            $businessUnitAddressTransfer = $this->companyUnitAddressMapper
                ->mapCompanyUnitAddressEntityToTransfer($companyUnitAddressEntity);
            $companyUnitAddresses->append($businessUnitAddressTransfer);
        }

        return $companyUnitAddresses;
    }
}
