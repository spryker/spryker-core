<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedBusinessFactory getFactory()
 */
class ProductDiscontinuedFacade extends AbstractFacade implements ProductDiscontinuedFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function markProductAsDiscontinued(
        ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedWriter()
            ->create($productDiscontinuedRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function unmarkProductAsDiscontinued(
        ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedWriter()
            ->delete($productDiscontinuedRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function findProductDiscontinuedByProductId(
        ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedReader()
            ->findProductDiscontinuedByProductId($productDiscontinuedRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer {
        return $this->getFactory()
            ->createProductDiscontinuedReader()
            ->findProductDiscontinuedCollection($criteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function deactivateDiscontinuedProducts(?LoggerInterface $logger = null): void
    {
        $this->getFactory()
            ->createProductDiscontinuedDeactivator($logger)
            ->deactivate();
    }
}
