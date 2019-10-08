<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiBusinessFactory getFactory()
 */
class DocumentationGeneratorRestApiFacade extends AbstractFacade implements DocumentationGeneratorRestApiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function generateDocumentation(): void
    {
        $this->getFactory()
            ->createDocumentationGenerator()
            ->generateDocumentation();
    }
}
