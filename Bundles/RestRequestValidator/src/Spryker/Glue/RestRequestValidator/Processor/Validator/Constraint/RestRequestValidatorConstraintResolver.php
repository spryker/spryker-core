<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface;
use Spryker\Glue\RestRequestValidator\Processor\Exception\ConstraintNotFoundException;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;

class RestRequestValidatorConstraintResolver implements RestRequestValidatorConstraintResolverInterface
{
    protected const FIELDS = 'fields';
    protected const CONSTRAINTS = 'constraints';

    /**
     * @var \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface
     */
    protected $restRequestValidatorConfigReader;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface
     */
    protected $constraintCollectionAdapter;

    /**
     * @var \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapterInterface $constraintCollectionAdapter
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface $restRequestValidatorConfigReader
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $config
     */
    public function __construct(
        RestRequestValidatorToConstraintCollectionAdapterInterface $constraintCollectionAdapter,
        RestRequestValidatorConfigReaderInterface $restRequestValidatorConfigReader,
        RestRequestValidatorConfig $config
    ) {
        $this->constraintCollectionAdapter = $constraintCollectionAdapter;
        $this->restRequestValidatorConfigReader = $restRequestValidatorConfigReader;
        $this->config = $config;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\Validator\Constraints\Collection|null
     */
    public function getConstraintCollection(RestRequestInterface $restRequest): ?Collection
    {
        $initializedConstraintCollection = $this->getConstraintFromConfig($restRequest);

        if (!$initializedConstraintCollection) {
            return null;
        }

        $constraints = $this->constraintCollectionAdapter->createCollection(
            [static::FIELDS => $initializedConstraintCollection] + $this->getConstraintCollectionOptions()
        );

        return $constraints;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array|null
     */
    protected function getConstraintFromConfig(RestRequestInterface $restRequest): ?array
    {
        $validationConfig = $this->restRequestValidatorConfigReader->findValidationConfiguration($restRequest);

        if (!$validationConfig) {
            return null;
        }

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
            $this->instantiateConstraintFromConfig(),
            $validators
        );
    }

    /**
     * @return callable
     */
    protected function instantiateConstraintFromConfig(): callable
    {
        return function (array $validatorConfig): Constraint {
            $shortClassName = key($validatorConfig);
            $parameters = $this->getParameters($validatorConfig);

            $className = $this->resolveConstraintClassName($shortClassName);

            if ($parameters !== null) {
                $parameters = reset($parameters);
            }

            return new $className($parameters);
        };
    }

    /**
     * @param string $className
     *
     * @throws \Spryker\Glue\RestRequestValidator\Processor\Exception\ConstraintNotFoundException
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

        throw new ConstraintNotFoundException($className, Response::HTTP_NOT_FOUND);
    }

    /**
     * @return array
     */
    protected function getConstraintCollectionOptions(): array
    {
        return $this->config->getConstraintCollectionOptions();
    }

    /**
     * @param array|null $constraintParameters
     *
     * @return array|null
     */
    protected function getParameters(?array $constraintParameters = null): ?array
    {
        if (!reset($constraintParameters)) {
            return null;
        }

        foreach ($constraintParameters as $constraintName => $constraintParameter) {
            if (!$constraintParameter) {
                continue;
            }

            $constraintParameters[$constraintName] = $constraintParameter = $this->processFieldsParameter($constraintParameter);
            $constraintParameters[$constraintName] = $constraintParameter = $this->processConstraintsParameter($constraintParameter);
        }

        return $constraintParameters;
    }

    /**
     * @param array|null $constraintParameters
     *
     * @return array|null
     */
    protected function processFieldsParameter(?array $constraintParameters): ?array
    {
        if (!isset($constraintParameters[static::FIELDS])) {
            return $constraintParameters;
        }

        foreach ($constraintParameters[static::FIELDS] as $fieldName => $validators) {
            if ($validators === null) {
                continue;
            }
            $constraintParameters[static::FIELDS][$fieldName] = $this->mapFieldConstrains($validators);
        }

        return $constraintParameters + $this->getConstraintCollectionOptions();
    }

    /**
     * @param array|null $constraintParameters
     *
     * @return array|null
     */
    protected function processConstraintsParameter(?array $constraintParameters): ?array
    {
        if (!isset($constraintParameters[static::CONSTRAINTS])) {
            return $constraintParameters;
        }
        $validators = $constraintParameters[static::CONSTRAINTS];

        if ($validators !== null) {
            $constraintParameters[static::CONSTRAINTS] = $this->mapFieldConstrains($validators);
        }

        return $constraintParameters;
    }
}
