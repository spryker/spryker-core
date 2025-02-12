<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxAppRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientInterface;
use Spryker\Glue\TaxAppRestApi\Processor\Validator\TaxIdValidator;
use Spryker\Glue\TaxAppRestApi\Processor\Validator\TaxIdValidatorInterface;

class TaxAppRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\TaxAppRestApi\Processor\Validator\TaxIdValidatorInterface
     */
    public function createTaxIdValidator(): TaxIdValidatorInterface
    {
        return new TaxIdValidator($this->getResourceBuilder(), $this->getTaxAppClient());
    }

    /**
     * @return \Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientInterface
     */
    public function getTaxAppClient(): TaxAppRestApiToTaxAppClientInterface
    {
        return $this->getProvidedDependency(TaxAppRestApiDependencyProvider::CLIENT_TAX_APP);
    }
}
