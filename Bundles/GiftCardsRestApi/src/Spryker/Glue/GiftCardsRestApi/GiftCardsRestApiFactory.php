<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi;

use Spryker\Glue\GiftCardsRestApi\Processor\Expander\GiftCardByQuoteResourceRelationshipExpander;
use Spryker\Glue\GiftCardsRestApi\Processor\Expander\GiftCardByQuoteResourceRelationshipExpanderInterface;
use Spryker\Glue\GiftCardsRestApi\Processor\Mapper\GiftCardsMapper;
use Spryker\Glue\GiftCardsRestApi\Processor\Mapper\GiftCardsMapperInterface;
use Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardsRestResponseBuilder;
use Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardsRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\GiftCardsRestApi\GiftCardsRestApiConfig getConfig()
 */
class GiftCardsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GiftCardsRestApi\Processor\Expander\GiftCardByQuoteResourceRelationshipExpanderInterface
     */
    public function createCartCodeByQuoteResourceRelationshipExpander(): GiftCardByQuoteResourceRelationshipExpanderInterface
    {
        return new GiftCardByQuoteResourceRelationshipExpander(
            $this->getResourceBuilder(),
            $this->createGiftCardsRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardsRestResponseBuilderInterface
     */
    public function createGiftCardsRestResponseBuilder(): GiftCardsRestResponseBuilderInterface
    {
        return new GiftCardsRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createGiftCardsMapper()
        );
    }

    /**
     * @return \Spryker\Glue\GiftCardsRestApi\Processor\Mapper\GiftCardsMapperInterface
     */
    public function createGiftCardsMapper(): GiftCardsMapperInterface
    {
        return new GiftCardsMapper();
    }
}
