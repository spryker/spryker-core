<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Api\Builder;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Spryker\Client\TaxApp\Dependency\Client\TaxAppToStoreClientInterface;

class TaxAppHeaderBuilder implements TaxAppHeaderBuilderInterface
{
    /**
     * @var string
     */
    protected const HEADER_STORE_REFERENCE = 'X-Store-Reference';

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
     *
     * @return array<string, string>
     */
    public function build(
        TaxCalculationRequestTransfer $taxCalculationRequestTransfer,
        StoreTransfer $storeTransfer
    ): array {
        $headers = [
            static::HEADER_STORE_REFERENCE => $this->getStoreReference($storeTransfer),
        ];

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
