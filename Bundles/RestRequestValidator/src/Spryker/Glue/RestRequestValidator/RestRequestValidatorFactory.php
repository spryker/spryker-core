<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator;

use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReader;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolver;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator;

/**
 * @method \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig getConfig()
 */
class RestRequestValidatorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface
     */
    public function createRestRequestValidator(): RestRequestValidatorInterface
    {
        return new RestRequestValidator(
            $this->createRestRequestConfigurationReader(),
            $this->createRestRequestValidatorConstraintResolver(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface
     */
    protected function createRestRequestConfigurationReader(): RestRequestValidatorConfigReaderInterface
    {
        return new RestRequestValidatorConfigReader(
            $this->getFilesystem(),
            $this->getYaml()
        );
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface
     */
    public function createRestRequestValidatorConstraintResolver(): RestRequestValidatorConstraintResolverInterface
    {
        return new RestRequestValidatorConstraintResolver($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    public function getFilesystem(): RestRequestValidatorToFilesystemAdapterInterface
    {
        return $this->getProvidedDependency(RestRequestValidatorDependencyProvider::FILESYSTEM);
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter
     */
    public function getYaml(): RestRequestValidatorToYamlAdapter
    {
        return $this->getProvidedDependency(RestRequestValidatorDependencyProvider::YAML);
    }
}
