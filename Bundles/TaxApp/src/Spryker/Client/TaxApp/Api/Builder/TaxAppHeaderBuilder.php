<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Api\Builder;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Spryker\Client\TaxApp\Dependency\Client\TaxAppToStoreClientInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

class TaxAppHeaderBuilder implements TaxAppHeaderBuilderInterface
{
    /**
     * @var string
     */
    protected const HEADER_STORE_REFERENCE = 'X-Store-Reference';

    /**
     * @var string
     */
    protected const HEADER_TENANT_IDENTIFIER = 'X-Tenant-Identifier';

    /**
     * @var string
     */
    protected const HEADER_AUTHORIZATION = 'Authorization';

    /**
     * @var \Spryker\Client\TaxApp\Dependency\Client\TaxAppToStoreClientInterface
     */
    protected TaxAppToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\TaxApp\Dependency\Client\TaxAppToStoreClientInterface $storeClient
     */
    public function __construct(TaxAppToStoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer $taxCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return array<string, string>
     */
    public function build(
        TaxCalculationRequestTransfer $taxCalculationRequestTransfer,
        StoreTransfer $storeTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer
    ): array {
        if (
            $taxAppConfigTransfer->getTenantIdentifier() === null &&
            ($storeTransfer->getStoreReference() === null && $storeTransfer->getName() === null)
        ) {
            throw new NullValueException('Tenant identifier or store reference or store name must be set.');
        }
        $headers = [];

        if ($taxAppConfigTransfer->getTenantIdentifier() !== null) {
            $headers[static::HEADER_TENANT_IDENTIFIER] = $taxAppConfigTransfer->getTenantIdentifier();
        }

        if ($storeTransfer->getStoreReference() || $storeTransfer->getName()) {
            $headers[static::HEADER_STORE_REFERENCE] = $this->getStoreReference($storeTransfer);
        }

        if (
            $taxCalculationRequestTransfer->offsetExists('authorization')
            && $taxCalculationRequestTransfer->getAuthorization()
        ) {
            $headers[static::HEADER_AUTHORIZATION] = $taxCalculationRequestTransfer->getAuthorization();
        }

        return $headers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function getStoreReference(StoreTransfer $storeTransfer): string
    {
        if ($storeTransfer->getStoreReference()) {
            return $storeTransfer->getStoreReference();
        }

        return $this->storeClient->getStoreByName($storeTransfer->getNameOrFail())->getStoreReferenceOrFail();
    }
}
