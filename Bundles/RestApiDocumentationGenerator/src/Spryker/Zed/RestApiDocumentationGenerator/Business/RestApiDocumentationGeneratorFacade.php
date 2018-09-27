<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorBusinessFactory getFactory()
 */
class RestApiDocumentationGeneratorFacade extends AbstractFacade implements RestApiDocumentationGeneratorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function generateOpenApiSpecification(): void
    {
        $this->getFactory()
            ->createRestApiDocumentationGenerator()
            ->generateOpenApiSpecification();
    }
}
