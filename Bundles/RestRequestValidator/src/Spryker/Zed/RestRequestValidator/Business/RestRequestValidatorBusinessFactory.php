<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilder;
use Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilderInterface;
use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollector;
use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface;
use Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinder;
use Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMerger;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface;
use Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaver;
use Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaverInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig getConfig()
 */
class RestRequestValidatorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilderInterface
     */
    public function createRestRequestValidatorBuilder(): RestRequestValidatorBuilderInterface
    {
        return new RestRequestValidatorBuilder(
            $this->createValidatorCollector(),
            $this->createValidatorMerger(),
            $this->createValidatorSaver()
        );
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface
     */
    protected function createValidatorCollector(): RestRequestValidatorCollectorInterface
    {
        return new RestRequestValidatorCollector($this->createSchemaFinder());
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface
     */
    protected function createValidatorMerger(): RestRequestValidatorMergerInterface
    {
        return new RestRequestValidatorMerger();
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaverInterface
     */
    protected function createValidatorSaver(): RestRequestValidatorSaverInterface
    {
        return new RestRequestValidatorSaver(
            $this->createFilesystem(),
            $this->getConfig()->getValidationSchemaCacheFile()
        );
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface
     */
    protected function createSchemaFinder(): RestRequestValidatorSchemaFinderInterface
    {
        return new RestRequestValidatorSchemaFinder(
            $this->getConfig()->getValidationSchemaPathPattern(),
            $this->getConfig()->getValidationSchemaFileNamePattern()
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function createFilesystem()
    {
        return new Filesystem();
    }
}
