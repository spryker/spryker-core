<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\IndexGenerator\Business\IndexGeneratorBusinessFactory getFactory()
 */
class IndexGeneratorFacade extends AbstractFacade implements IndexGeneratorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function generateIndexSchemaFiles(): void
    {
        $this->getFactory()
            ->createPostgresIndexGenerator()
            ->generateIndexes();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function removeIndexSchemaFiles(): void
    {
        $this->getFactory()
            ->createPostgresIndexRemover()
            ->removeIndexes();
    }
}
