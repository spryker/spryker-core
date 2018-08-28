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
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface $configReader
     */
    public function __construct(RestRequestValidatorConfigReaderInterface $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        if (!$restRequest->getResource()->getAttributes()) {
            return null;
        }

        $validationConfig = $this->configReader->getValidationConfiguration($restRequest->getResource()->getType(), $restRequest->getMetadata()->getMethod());

        $validationResult = $this->applyValidationToRequest($restRequest, $validationConfig);

        if (!$validationResult->count()) {
            return null;
        }

        return $this->formatResult($validationResult);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $validationConfig
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function applyValidationToRequest(RestRequestInterface $restRequest, array $validationConfig)
    {
        $validator = Validation::createValidator();

        $configResult = $this->initializeConstraintCollection($validationConfig);

        $constraints = new Collection($configResult);

        $violations = $validator->validate(
            $restRequest->getResource()->getAttributes()->toArray(),
            $constraints
        );

        return $violations;
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
            $configResult[$fieldName] = array_map(
                function ($param) {
                    if (!is_array($param)) {
                        $className = '\\Symfony\\Component\\Validator\\Constraints\\' . $param;
                        return new $className();
                    } else {
                        $className = '\\Symfony\\Component\\Validator\\Constraints\\' . key($param);
                        return new $className(reset($param));
                    }
                },
                $validators
            );
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
}
