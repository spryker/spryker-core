<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\RestRequestValidator\Business\Exception\ClassDoesNotExist;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;

class RestRequestValidatorConstraintResolver implements RestRequestValidatorConstraintResolverInterface
{
    protected const INSTANTIATE_CONSTRAINT_FROM_CONFIG_METHOD = 'instantiateConstraintFromConfig';

    /**
     * @var \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface
     */
    protected $configReader;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface
     */
    protected $constraintCollectionAdapter;

    /**
     * @param \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface $constraintCollectionAdapter
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface $configReader
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorToConstraintCollectionAdapterInterface $constraintCollectionAdapter,
        RestRequestValidatorConfigReaderInterface $configReader,
        RestRequestValidatorConfig $config
    ) {
        $this->constraintCollectionAdapter = $constraintCollectionAdapter;
        $this->configReader = $configReader;
        $this->config = $config;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\Validator\Constraints\Collection
     */
    public function initializeConstraintCollection(RestRequestInterface $restRequest): Collection
    {
        $initializedConstraintCollection = $this->initializeConstraintFromConfig($restRequest);
        $constraints = $this->constraintCollectionAdapter->createCollection(
            ['fields' => $initializedConstraintCollection] + $this->getDefaultValidationConfig()
        );

        return $constraints;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function initializeConstraintFromConfig(RestRequestInterface $restRequest): array
    {
        $validationConfig = $this->configReader->getValidationConfiguration($restRequest);
        $configResult = [];
        foreach ($validationConfig as $fieldName => $validators) {
            if ($validators !== null) {
                $configResult[$fieldName] = $this->mapFieldConstrains($validators);
            }
        }

        return $configResult;
    }

    /**
     * @param array $validators
     *
     * @return array
     */
    protected function mapFieldConstrains(array $validators): array
    {
        foreach ($validators as $key => $validator) {
            if (!is_array($validator)) {
                $validators[$key] = [$validator => null];
            }
        }

        return array_map(
            [$this, static::INSTANTIATE_CONSTRAINT_FROM_CONFIG_METHOD],
            $validators
        );
    }

    /**
     * @param array $classDeclaration
     *
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function instantiateConstraintFromConfig(array $classDeclaration): Constraint
    {
        $shortClassName = key($classDeclaration);
        $parameters = reset($classDeclaration);

        $className = $this->resolveConstraintClassName($shortClassName);

        return new $className($parameters);
    }

    /**
     * @param string $className
     *
     * @throws \Spryker\Glue\RestRequestValidator\Business\Exception\ClassDoesNotExist
     *
     * @return string|null
     */
    protected function resolveConstraintClassName(string $className): ?string
    {
        foreach ($this->config->getAvailableConstraintNamespaces() as $constraintNamespace) {
            if (class_exists($constraintNamespace . $className)) {
                return $constraintNamespace . $className;
            }
        }

        throw new ClassDoesNotExist(
            sprintf(RestRequestValidatorConfig::EXCEPTION_MESSAGE_CLASS_NOT_FOUND, $className),
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @return array
     */
    protected function getDefaultValidationConfig(): array
    {
        return $this->config->getDefaultValidationConfig();
    }
}
