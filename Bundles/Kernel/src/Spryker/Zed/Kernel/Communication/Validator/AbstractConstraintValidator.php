<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Validator;

use Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryResolver;
use Symfony\Component\Validator\ConstraintValidator;

abstract class AbstractConstraintValidator extends ConstraintValidator
{
    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private $factory;

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private function resolveFactory()
    {
        /** @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $factory */
        $factory = $this->getFactoryResolver()->resolve($this);

        return $factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryResolver
     */
    private function getFactoryResolver()
    {
        return new CommunicationFactoryResolver();
    }
}
