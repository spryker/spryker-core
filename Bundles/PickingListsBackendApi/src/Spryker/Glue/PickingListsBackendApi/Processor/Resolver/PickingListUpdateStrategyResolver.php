<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Resolver;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface;

class PickingListUpdateStrategyResolver implements PickingListUpdateStrategyResolverInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface
     */
    protected PickingListUpdateStrategyInterface $defaultPickingListUpdateStrategy;

    /**
     * @var list<\Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface>
     */
    protected array $pickingListUpdateStrategies;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface $defaultPickingListUpdateStrategy
     * @param list<\Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface> $pickingListUpdateStrategies
     */
    public function __construct(
        PickingListUpdateStrategyInterface $defaultPickingListUpdateStrategy,
        array $pickingListUpdateStrategies
    ) {
        $this->defaultPickingListUpdateStrategy = $defaultPickingListUpdateStrategy;
        $this->pickingListUpdateStrategies = $pickingListUpdateStrategies;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface
     */
    public function resolve(GlueRequestTransfer $glueRequestTransfer): PickingListUpdateStrategyInterface
    {
        foreach ($this->pickingListUpdateStrategies as $pickingListUpdateStrategy) {
            if ($pickingListUpdateStrategy->isApplicable($glueRequestTransfer)) {
                return $pickingListUpdateStrategy;
            }
        }

        return $this->defaultPickingListUpdateStrategy;
    }
}
