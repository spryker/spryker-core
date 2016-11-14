<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilText\Model\Slug;
use Spryker\Service\UtilText\Model\StringGenerator;

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
}
