<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsCartTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsTotalsTransfer;
use Generated\Shared\Transfer\RestQuoteRequestVersionTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class QuoteRequestMapper implements QuoteRequestMapperInterface
{
    /**
     * @var array<\Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface>
     */
    protected $restQuoteRequestAttributesExpanderPlugins;

    /**
     * @param array<\Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface> $restQuoteRequestAttributesExpanderPlugins
     */
    public function __construct(array $restQuoteRequestAttributesExpanderPlugins)
    {
        $this->restQuoteRequestAttributesExpanderPlugins = $restQuoteRequestAttributesExpanderPlugins;
    }

    /**
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers,
        string $localeName
    ): array {
        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            $restQuoteRequestsAttributesTransfer = $this->mapQuoteRequestTransferToRestQuoteRequestsAttributesTransfer(
                $quoteRequestTransfer,
                new RestQuoteRequestsAttributesTransfer(),
            );
            $restQuoteRequestsAttributesTransfers[] = $restQuoteRequestsAttributesTransfer;
        }

        return $this->expandRestQuoteRequestsAttributesTransfers($quoteRequestTransfers, $restQuoteRequestsAttributesTransfers, $localeName);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapRestRequestToQuoteRequestTransfer(
        RestRequestInterface $restRequestTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer {
        /** @var \Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer $quoteRequestsRequestAttributesTransfer */
        $quoteRequestsRequestAttributesTransfer = $restRequestTransfer->getResource()->getAttributes();
        $restUserTransfer = $restRequestTransfer->getRestUser();

        $quoteTransfer = (new QuoteTransfer())
            ->setUuid($quoteRequestsRequestAttributesTransfer->getCartUuid());

        if ($restUserTransfer !== null) {
            $companyUserTransfer = (new CompanyUserTransfer())
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser());

            $customerTransfer = (new CustomerTransfer())
                ->setIdCustomer($restUserTransfer->getSurrogateIdentifier())
                ->setCustomerReference($restUserTransfer->getNaturalIdentifier())
                ->setCompanyUserTransfer($companyUserTransfer);

            $quoteRequestTransfer->setCompanyUser($companyUserTransfer);

            $quoteTransfer->setCustomer($customerTransfer);
        }

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setMetadata($quoteRequestsRequestAttributesTransfer->getMetadata())
            ->setQuote($quoteTransfer);

        return $quoteRequestTransfer
            ->setLatestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer
     */
    protected function mapQuoteRequestTransferToRestQuoteRequestsAttributesTransfer(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
    ): RestQuoteRequestsAttributesTransfer {
        $quoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersion();
        if (!$quoteRequestVersionTransfer) {
            return $restQuoteRequestsAttributesTransfer;
        }

        $quoteTransfer = $quoteRequestVersionTransfer->getQuoteOrFail();

        $restQuoteRequestVersionTransfer = (new RestQuoteRequestVersionTransfer())
            ->fromArray($quoteRequestVersionTransfer->toArray(), true)
            ->setMetadata($quoteRequestVersionTransfer->getMetadata())
            ->setCart($this->mapQuoteTransferToRestQuoteRequestsCartTransfer(
                $quoteTransfer,
                new RestQuoteRequestsCartTransfer(),
            ));

        $restQuoteRequestsAttributesTransfer->fromArray($quoteRequestTransfer->toArray(), true)
            ->setShownVersion($restQuoteRequestVersionTransfer);

        $restQuoteRequestsAttributesTransfer->setVersions(
            $this->getQuoteRequestVersions($quoteRequestTransfer),
        );

        return $restQuoteRequestsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return array<string>
     */
    protected function getQuoteRequestVersions(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        $quoteRequestVersions = [];
        foreach ($quoteRequestTransfer->getQuoteRequestVersions() as $quoteRequestVersion) {
            if ($quoteRequestVersion->getVersionReference() !== null) {
                $quoteRequestVersions[] = $quoteRequestVersion->getVersionReference();
            }
        }

        return $quoteRequestVersions;
    }

    /**
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    protected function expandRestQuoteRequestsAttributesTransfers(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers,
        string $localeName
    ): array {
        foreach ($this->restQuoteRequestAttributesExpanderPlugins as $restQuoteRequestAttributesExpanderPlugin) {
            $restQuoteRequestsAttributesTransfers = $restQuoteRequestAttributesExpanderPlugin->expand(
                $restQuoteRequestsAttributesTransfers,
                $quoteRequestTransfers,
                $localeName,
            );
        }

        return $restQuoteRequestsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restQuoteRequestsCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function mapQuoteTransferToRestQuoteRequestsCartTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteRequestsCartTransfer $restQuoteRequestsCartTransfer
    ): RestQuoteRequestsCartTransfer {
        $restQuoteRequestsCartTransfer->fromArray($quoteTransfer->toArray(), true);

        $currencyTransfer = $quoteTransfer->getCurrency();
        if ($currencyTransfer !== null) {
            $restQuoteRequestsCartTransfer->setCurrency($currencyTransfer->getCode());
        }

        $storeTransfer = $quoteTransfer->getStore();
        if ($storeTransfer !== null) {
            $restQuoteRequestsCartTransfer->setStore($storeTransfer->getName());
        }

        $restQuoteRequestsCartTransfer = $this->mapTotalsTransferToRestQuoteRequestsTotalsTransfer(
            $quoteTransfer,
            $restQuoteRequestsCartTransfer,
        );

        return $restQuoteRequestsCartTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer $restQuoteRequestsCartTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsCartTransfer
     */
    protected function mapTotalsTransferToRestQuoteRequestsTotalsTransfer(
        QuoteTransfer $quoteTransfer,
        RestQuoteRequestsCartTransfer $restQuoteRequestsCartTransfer
    ): RestQuoteRequestsCartTransfer {
        $totalsTransfer = $quoteTransfer->getTotals();
        if ($totalsTransfer === null) {
            return $restQuoteRequestsCartTransfer;
        }

        $restQuoteRequestsTotalsTransfer = new RestQuoteRequestsTotalsTransfer();
        $restQuoteRequestsTotalsTransfer->fromArray($totalsTransfer->toArray(), true);
        $restQuoteRequestsCartTransfer->setTotals($restQuoteRequestsTotalsTransfer);

        return $restQuoteRequestsCartTransfer;
    }
}
