<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\AddReviewsTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReview\ProductReviewConfig getConfig()
 * @method \Spryker\Zed\ProductReview\Communication\ProductReviewCommunicationFactory getFactory()
 */
class ProductReviewAddReviewsMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddReviewsTransfer $addReviewsTransfer
     *
     * @return void
     */
    public function onAddReviews(AddReviewsTransfer $addReviewsTransfer): void
    {
        $this->getFacade()->handleAddReviews($addReviewsTransfer);
    }

    /**
     * {@inheritDoc}
     * Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield AddReviewsTransfer::class => [$this, 'onAddReviews'];
    }
}
