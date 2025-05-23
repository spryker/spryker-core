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
use Generated\Shared\Transfer\RestQuoteRequestCustomerTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsCartTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsTotalsTransfer;
use Generated\Shared\Transfer\RestQuoteRequestVersionTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;

class QuoteRequestMapper implements QuoteRequestMapperInterface
{
    /**
     * @var string
     */
    protected const FIELD_FILTER_VERSION = 'version';

    /**
     * @param list<\Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface> $restQuoteRequestAttributesExpanderPlugins
     */
    public function __construct(protected array $restQuoteRequestAttributesExpanderPlugins)
    {
    }

    /**
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isLatestVersionVisible
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers,
        RestRequestInterface $restRequest,
        bool $isLatestVersionVisible = true
    ): array {
        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            $restQuoteRequestsAttributesTransfer = $this->mapQuoteRequestTransferToRestQuoteRequestsAttributesTransfer(
                $quoteRequestTransfer,
                new RestQuoteRequestsAttributesTransfer(),
                $restRequest,
                $isLatestVersionVisible,
            );
            $restQuoteRequestsAttributesTransfers[] = $restQuoteRequestsAttributesTransfer;
        }

        return $this->expandRestQuoteRequestsAttributesTransfers(
            $quoteRequestTransfers,
            $restQuoteRequestsAttributesTransfers,
            $restRequest->getMetadata()->getLocale(),
        );
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
            ->setUuid($quoteRequestsRequestAttributesTransfer->getCartUuidOrFail());

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
     * @param \Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer $restQuoteRequestsRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapRestQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
        RestQuoteRequestsRequestAttributesTransfer $restQuoteRequestsRequestAttributesTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer {
        $quoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersionOrFail();

        if ($restQuoteRequestsRequestAttributesTransfer->getMetadata()) {
            $quoteRequestVersionTransfer->setMetadata($restQuoteRequestsRequestAttributesTransfer->getMetadata());
        }

        if ($restQuoteRequestsRequestAttributesTransfer->getCartUuid()) {
            $quoteRequestVersionTransfer->setQuote(
                (new QuoteTransfer())->setUuid($restQuoteRequestsRequestAttributesTransfer->getCartUuidOrFail()),
            );
        }

        return $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestCustomerTransfer $restQuoteRequestCustomerTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestCustomerTransfer
     */
    protected function mapQuoteRequestTransferToRestQuoteRequestCustomerTransfer(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestCustomerTransfer $restQuoteRequestCustomerTransfer
    ): RestQuoteRequestCustomerTransfer {
        if (!$quoteRequestTransfer->getCompanyUser()) {
            return $restQuoteRequestCustomerTransfer;
        }
        if ($quoteRequestTransfer->getCompanyUser()->getCustomer()) {
            $restQuoteRequestCustomerTransfer->fromArray(
                $quoteRequestTransfer->getCompanyUser()->getCustomer()->toArray(),
                true,
            );
        }

        $restQuoteRequestCustomerTransfer
            ->setIdCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser())
            ->setIdCompany($quoteRequestTransfer->getCompanyUser()->getFkCompany())
            ->setIdCompanyBusinessUnit($quoteRequestTransfer->getCompanyUser()->getFkCompanyBusinessUnit());

        return $restQuoteRequestCustomerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isLatestVersionVisible
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer
     */
    protected function mapQuoteRequestTransferToRestQuoteRequestsAttributesTransfer(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer,
        RestRequestInterface $restRequest,
        bool $isLatestVersionVisible
    ): RestQuoteRequestsAttributesTransfer {
        $quoteRequestVersionTransfer = $this->findConcreteVersion($quoteRequestTransfer, $restRequest, $isLatestVersionVisible);
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

        $restQuoteRequestCustomerTransfer = $this->mapQuoteRequestTransferToRestQuoteRequestCustomerTransfer(
            $quoteRequestTransfer,
            new RestQuoteRequestCustomerTransfer(),
        );

        $restQuoteRequestsAttributesTransfer
            ->fromArray($quoteRequestTransfer->toArray(), true)
            ->setShownVersion($restQuoteRequestVersionTransfer)
            ->setCustomer($restQuoteRequestCustomerTransfer);

        $restQuoteRequestsAttributesTransfer->setVersions(
            $this->getQuoteRequestVersions($quoteRequestTransfer, $isLatestVersionVisible),
        );

        return $restQuoteRequestsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param bool $isLatestVersionVisible
     *
     * @return list<string>
     */
    protected function getQuoteRequestVersions(
        QuoteRequestTransfer $quoteRequestTransfer,
        bool $isLatestVersionVisible
    ): array {
        $quoteRequestVersionTransfers = $quoteRequestTransfer
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        if (!$isLatestVersionVisible && !$quoteRequestTransfer->getIsLatestVersionVisible()) {
            array_shift($quoteRequestVersionTransfers);
        }

        $quoteRequestVersions = [];
        foreach ($quoteRequestVersionTransfers as $quoteRequestVersion) {
            if ($quoteRequestVersion->getVersionReference() !== null) {
                $quoteRequestVersions[] = $quoteRequestVersion->getVersionReferenceOrFail();
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

        return $this->mapTotalsTransferToRestQuoteRequestsTotalsTransfer(
            $quoteTransfer,
            $restQuoteRequestsCartTransfer,
        );
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

        return $restQuoteRequestsCartTransfer->setTotals(
            (new RestQuoteRequestsTotalsTransfer())->fromArray($totalsTransfer->toArray(), true),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isLatestVersionVisible
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    protected function findConcreteVersion(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestRequestInterface $restRequest,
        bool $isLatestVersionVisible
    ): ?QuoteRequestVersionTransfer {
        $selectedVersion = null;
        if ($restRequest->hasFilters(QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS)) {
            foreach ($restRequest->getFiltersByResource(QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS) as $glueFilterTransfer) {
                if ($glueFilterTransfer->getField() === static::FIELD_FILTER_VERSION) {
                    $selectedVersion = $glueFilterTransfer->getValue();

                    break;
                }
            }
        }

        if (!$selectedVersion) {
            return $isLatestVersionVisible ? $quoteRequestTransfer->getLatestVersion() : $quoteRequestTransfer->getLatestVisibleVersion();
        }

        $quoteRequestVersionTransfers = $quoteRequestTransfer->getQuoteRequestVersions()->getArrayCopy();
        if (count($quoteRequestVersionTransfers) !== 1 && !$isLatestVersionVisible) {
            array_shift($quoteRequestVersionTransfers);
        }

        foreach ($quoteRequestVersionTransfers as $quoteRequestVersionTransfer) {
            if ($quoteRequestVersionTransfer->getVersionReference() === $selectedVersion) {
                return $quoteRequestVersionTransfer;
            }
        }

        return null;
    }
}
