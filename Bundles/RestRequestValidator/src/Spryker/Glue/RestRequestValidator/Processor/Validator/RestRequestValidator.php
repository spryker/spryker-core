<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapterInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RestRequestValidator implements RestRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface
     */
    protected $restRequestValidatorConstraintResolver;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @var \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface $restRequestValidatorConstraintResolver
     * @param \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorConstraintResolverInterface $restRequestValidatorConstraintResolver,
        RestRequestValidatorToValidationAdapterInterface $validationAdapter,
        RestRequestValidatorConfig $config
    ) {
        $this->restRequestValidatorConstraintResolver = $restRequestValidatorConstraintResolver;
        $this->validationAdapter = $validationAdapter;
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        if (!$this->isRequestRequireValidation($restRequest) || !$restRequest->getResource()->getAttributes()) {
            return null;
        }

        $constraintCollection = $this->restRequestValidatorConstraintResolver->getConstraintCollection($restRequest);
        if (!$constraintCollection) {
            return null;
        }

        return $this->validateRequest($restRequest, $constraintCollection);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Symfony\Component\Validator\Constraints\Collection $constraintCollection
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    protected function validateRequest(RestRequestInterface $restRequest, Collection $constraintCollection): ?RestErrorCollectionTransfer
    {
        $validator = $this->validationAdapter->createValidator();
        if (!$restRequest->getAttributesDataFromRequest()) {
            return null;
        }

        $violations = $validator->validate(
            $restRequest->getAttributesDataFromRequest(),
            $constraintCollection
        );

        if (!$violations->count()) {
            return null;
        }

        return $this->formatResult($violations);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $validationResult
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function formatResult(ConstraintViolationListInterface $validationResult): RestErrorCollectionTransfer
    {
        $restErrorCollection = new RestErrorCollectionTransfer();
        foreach ($validationResult as $validationError) {
            $restErrorCollection->addRestError(
                (new RestErrorMessageTransfer())
                    ->setCode(RestRequestValidatorConfig::RESPONSE_CODE_REQUEST_INVALID)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail($validationError->getPropertyPath() . ' => ' . $validationError->getMessage())
            );
        }

        return $restErrorCollection;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isRequestRequireValidation(RestRequestInterface $restRequest): bool
    {
        return in_array($restRequest->getMetadata()->getMethod(), $this->config->getHttpMethodsThatRequireValidation());
    }
}
