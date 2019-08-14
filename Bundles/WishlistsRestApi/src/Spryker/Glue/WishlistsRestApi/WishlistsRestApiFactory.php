<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistRelationshipExpanderByResourceId;
use Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistRelationshipExpanderByResourceIdInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapper;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapper;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilder;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemWriter;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemWriterInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistCreator;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistCreatorInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistDeleter;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistDeleterInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReader;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistUpdater;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistUpdaterInterface;

/**
 * @method \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface getClient()
 * @method \Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface
     */
    public function createWishlistReader(): WishlistReaderInterface
    {
        return new WishlistReader(
            $this->getWishlistClient(),
            $this->getResourceBuilder(),
            $this->createWishlistsResourceMapper(),
            $this->createWishlistItemsResourceMapper(),
            $this->getClient(),
            $this->createWishlistRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistCreatorInterface
     */
    public function createWishlistCreator(): WishlistCreatorInterface
    {
        return new WishlistCreator(
            $this->getWishlistClient(),
            $this->createWishlistsResourceMapper(),
            $this->createWishlistRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistDeleterInterface
     */
    public function createWishlistDeleter(): WishlistDeleterInterface
    {
        return new WishlistDeleter(
            $this->getClient(),
            $this->createWishlistRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistUpdaterInterface
     */
    public function createWishlistUpdater(): WishlistUpdaterInterface
    {
        return new WishlistUpdater(
            $this->createWishlistsResourceMapper(),
            $this->getClient(),
            $this->createWishlistRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemWriterInterface
     */
    public function createWishlistItemsWriter(): WishlistItemWriterInterface
    {
        return new WishlistItemWriter(
            $this->getWishlistClient(),
            $this->getResourceBuilder(),
            $this->createWishlistItemsResourceMapper(),
            $this->createWishlistReader()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    public function createWishlistRestResponseBuilder(): WishlistRestResponseBuilderInterface
    {
        return new WishlistRestResponseBuilder(
            $this->getConfig(),
            $this->getResourceBuilder(),
            $this->createWishlistsResourceMapper(),
            $this->createWishlistItemsResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    public function getWishlistClient(): WishlistsRestApiToWishlistClientInterface
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::CLIENT_WISHLIST);
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface
     */
    public function createWishlistsResourceMapper(): WishlistMapperInterface
    {
        return new WishlistMapper();
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface
     */
    public function createWishlistItemsResourceMapper(): WishlistItemMapperInterface
    {
        return new WishlistItemMapper();
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistRelationshipExpanderByResourceIdInterface
     */
    public function createWishlistRelationshipExpanderByResourceId(): WishlistRelationshipExpanderByResourceIdInterface
    {
        return new WishlistRelationshipExpanderByResourceId($this->createWishlistReader());
    }
}
