<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Api\Builder;

use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Spryker\Client\TaxApp\Dependency\Client\TaxAppToStoreClientInterface;
use Spryker\Client\TaxApp\Exception\TaxAppInvalidConfigException;
use Spryker\Client\TaxApp\TaxAppConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class TaxAppHeaderBuilder implements TaxAppHeaderBuilderInterface
{
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
     * @var \Spryker\Client\TaxApp\TaxAppConfig
     */
    protected TaxAppConfig $taxAppConfig;

    /**
     * @param \Spryker\Client\TaxApp\Dependency\Client\TaxAppToStoreClientInterface $storeClient
     * @param \Spryker\Client\TaxApp\TaxAppConfig $taxAppConfig
     */
    public function __construct(
        TaxAppToStoreClientInterface $storeClient,
        TaxAppConfig $taxAppConfig
    ) {
        $this->storeClient = $storeClient;
        $this->taxAppConfig = $taxAppConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer|\Generated\Shared\Transfer\TaxRefundRequestTransfer $taxRequestTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @throws \Spryker\Client\TaxApp\Exception\TaxAppInvalidConfigException
     *
     * @return array<string, string>
     */
    public function build(
        AbstractTransfer $taxRequestTransfer,
        StoreTransfer $storeTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer
    ): array {
        if (
            !$this->taxAppConfig->getTenantIdentifier()
            && ($storeTransfer->getStoreReference() === null && $storeTransfer->getName() === null)
        ) {
            throw new TaxAppInvalidConfigException('Tenant identifier or store reference or store name must be set.');
        }

        $headers = [
            static::HEADER_TENANT_IDENTIFIER => $this->taxAppConfig->getTenantIdentifier() ?: $this->findStoreReference($storeTransfer),
        ];

        if (
            $taxRequestTransfer->offsetExists('authorization')
            && $taxRequestTransfer->offsetGet('authorization')
        ) {
            $headers[static::HEADER_AUTHORIZATION] = $taxRequestTransfer->offsetGet('authorization');
        }

        return $headers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string|null
     */
    protected function findStoreReference(StoreTransfer $storeTransfer): ?string
    {
        if ($storeTransfer->getStoreReference()) {
            return $storeTransfer->getStoreReference();
        }

        return $this->storeClient->getStoreByName($storeTransfer->getNameOrFail())->getStoreReference();
    }
}
