<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi;

use Spryker\Glue\GiftCardsRestApi\Processor\Expander\GiftCardByQuoteResourceRelationshipExpander;
use Spryker\Glue\GiftCardsRestApi\Processor\Expander\GiftCardByQuoteResourceRelationshipExpanderInterface;
use Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardRestResponseBuilder;
use Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\GiftCardsRestApi\GiftCardsRestApiConfig getConfig()
 */
class GiftCardsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GiftCardsRestApi\Processor\Expander\GiftCardByQuoteResourceRelationshipExpanderInterface
     */
    public function createGiftCardByQuoteResourceRelationshipExpander(): GiftCardByQuoteResourceRelationshipExpanderInterface
    {
        return new GiftCardByQuoteResourceRelationshipExpander(
            $this->createGiftCardRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardRestResponseBuilderInterface
     */
    public function createGiftCardRestResponseBuilder(): GiftCardRestResponseBuilderInterface
    {
        return new GiftCardRestResponseBuilder(
            $this->getResourceBuilder()
        );
    }
}
