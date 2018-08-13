<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SwaggerGenerator\Business\SwaggerGeneratorBusinessFactory getFactory()
 */
class SwaggerGeneratorFacade extends AbstractFacade implements SwaggerGeneratorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function generate(): void
    {
        $this->getFactory()
            ->createGenerator()
            ->generate();
    }
}
