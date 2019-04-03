<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\RestCompanyAttributesTransfer;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface;
use Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CompanyByPayloadResourceRelationshipExpander implements CompanyByPayloadResourceRelationshipExpanderInterface
{
    protected const PROPERTY_COMPANY = 'company';

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface
     */
    protected $companyRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface
     */
    protected $companyMapper;

    /**
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\RestResponseBuilder\CompanyRestResponseBuilderInterface $companyRestResponseBuilder
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface $companyMapper
     */
    public function __construct(
        CompanyRestResponseBuilderInterface $companyRestResponseBuilder,
        CompanyMapperInterface $companyMapper
    ) {
        $this->companyRestResponseBuilder = $companyRestResponseBuilder;
        $this->companyMapper = $companyMapper;
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
             * @var \Generated\Shared\Transfer\CompanyUserTransfer|\Generated\Shared\Transfer\CompanyRoleTransfer|\Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $payload
             */
            $payload = $resource->getPayload();

            if (!$this->isValidPayload($payload)) {
                continue;
            }

            $companyTransfer = $payload->offsetGet(static::PROPERTY_COMPANY);

            $restCompanyAttributesTransfer = $this->companyMapper
                ->mapCompanyTransferToRestCompanyAttributesTransfer(
                    $companyTransfer,
                    new RestCompanyAttributesTransfer()
                );

            $resource->addRelationship(
                $this->companyRestResponseBuilder->buildCompanyRestResource(
                    $companyTransfer->getUuid(),
                    $restCompanyAttributesTransfer
                )
            );
        }

        return $resources;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $payload
     *
     * @return bool
     */
    protected function isCompanyTransferProvidedInPayload(AbstractTransfer $payload): bool
    {
        return $payload->offsetExists(static::PROPERTY_COMPANY) && $payload->offsetGet(static::PROPERTY_COMPANY) instanceof CompanyTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $payload
     *
     * @return bool
     */
    protected function isValidPayload(?AbstractTransfer $payload = null): bool
    {
        return $payload && $this->isCompanyTransferProvidedInPayload($payload);
    }
}
