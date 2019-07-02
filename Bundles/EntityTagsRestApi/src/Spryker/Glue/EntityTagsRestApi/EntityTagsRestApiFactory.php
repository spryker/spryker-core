<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi;

use Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagChecker;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagCheckerInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagRequestValidator;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagRequestValidatorInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResolver;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResolverInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResponseHeaderFormatter;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResponseHeaderFormatterInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\RestResponseBuilder\EntityTagRestResponseBuilder;
use Spryker\Glue\EntityTagsRestApi\Processor\RestResponseBuilder\EntityTagRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig getConfig()
 */
class EntityTagsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagCheckerInterface
     */
    public function createEntityTagChecker(): EntityTagCheckerInterface
    {
        return new EntityTagChecker($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResolverInterface
     */
    public function createEntityTagResolver(): EntityTagResolverInterface
    {
        return new EntityTagResolver(
            $this->createEntityTagChecker(),
            $this->getEntityTagClient()
        );
    }

    /**
     * @return \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResponseHeaderFormatterInterface
     */
    public function createEntityTagResponseHeaderFormatter(): EntityTagResponseHeaderFormatterInterface
    {
        return new EntityTagResponseHeaderFormatter(
            $this->createEntityTagChecker(),
            $this->createEntityTagResolver(),
            $this->getEntityTagClient()
        );
    }

    /**
     * @return \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagRequestValidatorInterface
     */
    public function createEntityTagRequestValidator(): EntityTagRequestValidatorInterface
    {
        return new EntityTagRequestValidator(
            $this->createEntityTagChecker(),
            $this->getEntityTagClient(),
            $this->createEntityTagRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\EntityTagsRestApi\Processor\RestResponseBuilder\EntityTagRestResponseBuilderInterface
     */
    public function createEntityTagRestResponseBuilder(): EntityTagRestResponseBuilderInterface
    {
        return new EntityTagRestResponseBuilder();
    }

    /**
     * @return \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface
     */
    public function getEntityTagClient(): EntityTagsRestApiToEntityTagClientInterface
    {
        return $this->getProvidedDependency(EntityTagsRestApiDependencyProvider::CLIENT_ENTITY_TAG);
    }
}
