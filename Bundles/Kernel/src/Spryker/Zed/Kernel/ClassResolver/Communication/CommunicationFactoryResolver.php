<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Communication;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class CommunicationFactoryResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ZedFactoryCommunication';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|null $resolved */
        $resolved = parent::doResolve($callerClass);

        if ($resolved === null) {
            throw new CommunicationFactoryNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
