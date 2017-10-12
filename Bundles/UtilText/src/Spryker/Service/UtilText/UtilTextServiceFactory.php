<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilText\Model\Filter\CamelCaseToSeparator;
use Spryker\Service\UtilText\Model\Filter\SeparatorToCamelCase;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Service\UtilText\Model\Slug;
use Spryker\Service\UtilText\Model\StringGenerator;
use Spryker\Service\UtilText\Model\Token\Token;

class UtilTextServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilText\Model\SlugInterface
     */
    public function createTextSlug()
    {
        return new Slug();
    }

    /**
     * @return \Spryker\Service\UtilText\Model\StringGeneratorInterface
     */
    public function createStringGenerator()
    {
        return new StringGenerator();
    }

    /**
     * @return \Spryker\Service\UtilText\Model\HashInterface
     */
    public function createHash()
    {
        return new Hash();
    }

    /**
     * @return \Spryker\Service\UtilText\Model\Filter\CamelCaseToSeparatorInterface
     */
    public function createCamelCaseToSeparator()
    {
        return new CamelCaseToSeparator();
    }

    /**
     * @return \Spryker\Service\UtilText\Model\Filter\SeparatorToCamelCaseInterface
     */
    public function createSeparatorToCamelCase()
    {
        return new SeparatorToCamelCase();
    }

    /**
     * @return \Spryker\Service\UtilText\Model\Token\TokenInterface
     */
    public function createToken()
    {
        return new Token();
    }

    /**
     * @return \Spryker\Service\UtilText\Model\Token\TokenInterface
     */
    public function createUrl()
    {
        return new Token();
    }
}
