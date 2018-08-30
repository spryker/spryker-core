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
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

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
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface $configReader
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface $constraintResolver
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorConfigReaderInterface $configReader,
        RestRequestValidatorConstraintResolverInterface $constraintResolver,
        RestRequestValidatorConfig $config
    ) {
        $this->configReader = $configReader;
        $this->constraintResolver = $constraintResolver;
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
        $validator = Validation::createValidator();
        $constraints = new Collection(
            ['fields' => $this->initializeConstraintCollection($validationConfig)] + $this->getDefaultValidationConfig()
        );
        $fieldsToValidate = $this->getFieldsForValidation($restRequest);

        $violations = $validator->validate(
            $fieldsToValidate,
            $constraints
        );

        return $this->formatResult($violations);
    }

    /**
     * @param array $validationConfig
     *
     * @return array
     */
    protected function initializeConstraintCollection(array $validationConfig): array
    {
        $configResult = [];
        foreach ($validationConfig as $fieldName => $validators) {
            $configResult[$fieldName] = $this->mapFieldConstrains($validators);
        }

        return $configResult;
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
            $restErrorCollection->addRestErrors(
                (new RestErrorMessageTransfer())
                    ->setCode(RestRequestValidatorConfig::RESPONSE_CODE_REQUEST_INVALID)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail($validationError->getPropertyPath() . ' => ' . $validationError->getMessage())
            );
        }

        return $restErrorCollection;
    }

    /**
     * @param array $validators
     *
     * @return array
     */
    protected function mapFieldConstrains(array $validators): array
    {
        return array_map(
            function ($classDeclaration) {
                $shortClassName = null;
                $parameters = null;
                if (!is_array($classDeclaration)) {
                    $shortClassName = $classDeclaration;
                } else {
                    $shortClassName = key($classDeclaration);
                    $parameters = reset($classDeclaration);
                }

                $className = $this->constraintResolver->resolveConstraintClassName($shortClassName);
                return new $className($parameters);
            },
            $validators
        );
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
        return ['allowExtraFields' => true, 'groups' => ['Default']];
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
