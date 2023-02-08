<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage\Mapper;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToUtilEncodingServiceInterface;

class CustomerStorageMapper implements CustomerStorageMapperInterface
{
    /**
     * @var \Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToUtilEncodingServiceInterface
     */
    protected CustomerStorageToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        CustomerStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<string, mixed> $customerInvalidatedStorageDataCollectionIndexedByCustomerReference
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function mapCustomerInvalidatedStorageDataCollectionToInvalidatedCustomerCollectionTransfer(
        array $customerInvalidatedStorageDataCollectionIndexedByCustomerReference,
        InvalidatedCustomerCollectionTransfer $invalidatedCustomerCollectionTransfer
    ): InvalidatedCustomerCollectionTransfer {
        foreach ($customerInvalidatedStorageDataCollectionIndexedByCustomerReference as $customerReference => $jsonData) {
            if (is_string($jsonData)) {
                $jsonData = $this->utilEncodingService->decodeJson($jsonData, true);
            }

            if ($jsonData === null) {
                continue;
            }

            $invalidatedCustomerCollectionTransfer->addInvalidatedCustomer(
                (new InvalidatedCustomerTransfer())
                    ->fromArray($jsonData, true)
                    ->setCustomerReference($customerReference),
            );
        }

        return $invalidatedCustomerCollectionTransfer;
    }
}
