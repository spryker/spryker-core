<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder;

use Everon\Component\CriteriaBuilder\CriteriaBuilderFactoryWorker as EveronCriteriaBuilderFactoryWorker;

class CriteriaBuilderFactoryWorker extends EveronCriteriaBuilderFactoryWorker implements CriteriaBuilderFactoryWorkerInterface
{
    /**
     * @inheritDoc
     */
    public function buildCriteriaBuilder()
    {
        $CriteriaBuilder = new CriteriaBuilder();
        $this->getFactory()->injectDependencies(CriteriaBuilder::class, $CriteriaBuilder);

        return $CriteriaBuilder;
    }
}
