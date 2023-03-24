<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Resolver\PickingListUpdateStrategyResolverInterface;

class PickingListUpdater implements PickingListUpdaterInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Resolver\PickingListUpdateStrategyResolverInterface
     */
    protected PickingListUpdateStrategyResolverInterface $pickingListUpdateStrategyResolver;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface
     */
    protected PickingListResponseCreatorInterface $pickingListResponseCreator;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Resolver\PickingListUpdateStrategyResolverInterface $pickingListUpdateStrategyResolver
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Creator\PickingListResponseCreatorInterface $pickingListResponseCreator
     */
    public function __construct(
        PickingListUpdateStrategyResolverInterface $pickingListUpdateStrategyResolver,
        PickingListResponseCreatorInterface $pickingListResponseCreator
    ) {
        $this->pickingListUpdateStrategyResolver = $pickingListUpdateStrategyResolver;
        $this->pickingListResponseCreator = $pickingListResponseCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function update(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $pickingListUpdateStrategy = $this->pickingListUpdateStrategyResolver
            ->resolve($glueRequestTransfer);

        return $this->createPickingListUpdateResponse(
            $glueRequestTransfer,
            $pickingListUpdateStrategy->update($glueRequestTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createPickingListUpdateResponse(
        GlueRequestTransfer $glueRequestTransfer,
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): GlueResponseTransfer {
        /** @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $pickingListCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            return $this->pickingListResponseCreator
                ->createPickingListErrorResponse(
                    $errorTransfers,
                    $glueRequestTransfer->getLocale(),
                );
        }

        /** @var \ArrayObject<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers */
        $pickingListTransfers = $pickingListCollectionResponseTransfer->getPickingLists();

        return $this->pickingListResponseCreator->createPickingListSuccessfulResponse($pickingListTransfers);
    }
}
