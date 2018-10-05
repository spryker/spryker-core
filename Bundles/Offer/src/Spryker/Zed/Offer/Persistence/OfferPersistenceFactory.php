<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence;

use Orm\Zed\Offer\Persistence\SpyOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Offer\Dependency\Service\OfferToUtilEncodingServiceInterface;
use Spryker\Zed\Offer\OfferDependencyProvider;
use Spryker\Zed\Offer\Persistence\Mapper\OfferMapper;
use Spryker\Zed\Offer\Persistence\Mapper\OfferMapperInterface;

/**
 * @method \Spryker\Zed\Offer\OfferConfig getConfig()
 */
class OfferPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Offer\Persistence\SpyOfferQuery
     */
    public function createPropelOfferQuery()
    {
        return SpyOfferQuery::create();
    }

    /**
     * @return \Spryker\Zed\Offer\Persistence\Mapper\OfferMapperInterface
     */
    public function createOfferMapper(): OfferMapperInterface
    {
        return new OfferMapper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Dependency\Service\OfferToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OfferToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OfferDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
