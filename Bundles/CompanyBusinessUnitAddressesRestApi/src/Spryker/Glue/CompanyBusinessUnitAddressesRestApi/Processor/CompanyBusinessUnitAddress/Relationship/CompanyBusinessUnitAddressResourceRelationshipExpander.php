<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Relationship;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Mapper\CompanyBusinessUnitAddressMapperInterface;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder\CompanyBusinessUnitAddressRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitAddressResourceRelationshipExpander implements CompanyBusinessUnitAddressResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder\CompanyBusinessUnitAddressRestResponseBuilderInterface
     */
    protected $companyBusinessUnitAddressRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Mapper\CompanyBusinessUnitAddressMapperInterface
     */
    protected $companyBusinessUnitMapper;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder\CompanyBusinessUnitAddressRestResponseBuilderInterface $companyBusinessUnitAddressRestResponseBuilder
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Mapper\CompanyBusinessUnitAddressMapperInterface $companyBusinessUnitAddressMapper
     */
    public function __construct(
        CompanyBusinessUnitAddressRestResponseBuilderInterface $companyBusinessUnitAddressRestResponseBuilder,
        CompanyBusinessUnitAddressMapperInterface $companyBusinessUnitAddressMapper
    ) {
        $this->companyBusinessUnitAddressRestResponseBuilder = $companyBusinessUnitAddressRestResponseBuilder;
        $this->companyBusinessUnitMapper = $companyBusinessUnitAddressMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $payload
             */
            $payload = $resource->getPayload();
            $addressCollectionTransfer = $payload->getAddressCollection();

            if (!$this->isValidPayloadType($payload) || !$this->hasAddressCollection($addressCollectionTransfer)) {
                continue;
            }

            foreach ($addressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddress) {
                $resource->addRelationship($this->createCompanyBusinessUnitAddressResource(
                    $companyUnitAddress
                ));
            }
        }

        return $resources;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCompanyBusinessUnitAddressResource(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): RestResourceInterface {
        $restCompanyBusinessUnitAttributesTransfer = $this->companyBusinessUnitMapper
            ->mapCompanyUnitAddressTransferToRestCompanyBusinessUnitAddressAttributesTransfer(
                $companyUnitAddressTransfer,
                new RestCompanyBusinessUnitAddressAttributesTransfer()
            );

        return $this->companyBusinessUnitAddressRestResponseBuilder->buildCompanyBusinessUnitAddressRestResource(
            $restCompanyBusinessUnitAttributesTransfer,
            $companyUnitAddressTransfer->getUuid()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $payload
     *
     * @return bool
     */
    protected function isValidPayloadType(?CompanyBusinessUnitTransfer $payload = null): bool
    {
        return $payload && $payload instanceof CompanyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer|null $addressCollectionTransfer
     *
     * @return bool
     */
    protected function hasAddressCollection(?CompanyUnitAddressCollectionTransfer $addressCollectionTransfer = null): bool
    {
        return $addressCollectionTransfer && $addressCollectionTransfer->getCompanyUnitAddresses()->count();
    }
}
