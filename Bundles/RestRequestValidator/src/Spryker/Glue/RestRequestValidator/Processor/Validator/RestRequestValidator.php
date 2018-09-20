<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapterInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RestRequestValidator implements RestRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface
     */
    protected $configReader;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface
     */
    protected $constraintResolver;

    /**
     * @var \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface
     */
    protected $constraintCollectionAdapter;

    /**
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface $configReader
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface $constraintResolver
     * @param \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface $constraintCollectionAdapter
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorConfigReaderInterface $configReader,
        RestRequestValidatorConstraintResolverInterface $constraintResolver,
        RestRequestValidatorToValidationAdapterInterface $validationAdapter,
        RestRequestValidatorToConstraintCollectionAdapterInterface $constraintCollectionAdapter,
        RestRequestValidatorConfig $config
    ) {
        $this->configReader = $configReader;
        $this->constraintResolver = $constraintResolver;
        $this->validationAdapter = $validationAdapter;
        $this->constraintCollectionAdapter = $constraintCollectionAdapter;
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
        if (!$restRequest->getResource()->getAttributes() || !$this->isSubjectByMethod($restRequest)) {
            return null;
        }
        $validationConfig = $this->configReader->getValidationConfiguration($restRequest);
        $validationResult = $this->applyValidationToRequest($restRequest, $validationConfig);
        if (!$validationResult->getRestErrors()->count()) {
            return null;
        }

        return $validationResult;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $validationConfig
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function applyValidationToRequest(RestRequestInterface $restRequest, array $validationConfig): RestErrorCollectionTransfer
    {
        $validator = $this->validationAdapter->createValidator();
        $initializedConstraintCollection = $this->constraintResolver->initializeConstraintCollection($validationConfig);
        $constraints = $this->constraintCollectionAdapter->createCollection(
            ['fields' => $initializedConstraintCollection] + $this->getDefaultValidationConfig()
        );
        $fieldsToValidate = $this->getFieldsForValidation($restRequest);

        $violations = $validator->validate(
            $fieldsToValidate,
            $constraints
        );

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
     * @return array
     */
    protected function getFieldsForValidation(RestRequestInterface $restRequest): array
    {
        return $restRequest->getRawPostData();
    }

    /**
     * @return array
     */
    protected function getDefaultValidationConfig(): array
    {
        return $this->config->getDefaultValidationConfig();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isSubjectByMethod(RestRequestInterface $restRequest): bool
    {
        return in_array($restRequest->getMetadata()->getMethod(), $this->config->getAvailableMethods());
    }
}
