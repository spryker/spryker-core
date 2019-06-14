<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Form;

use Spryker\Yves\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Yves\Kernel\ClientResolverAwareTrait;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\Form\AbstractType as SymfonyAbstractType;

abstract class AbstractType extends SymfonyAbstractType
{
    use BundleConfigResolverAwareTrait;
    use FactoryResolverAwareTrait;
    use ClientResolverAwareTrait;
}
