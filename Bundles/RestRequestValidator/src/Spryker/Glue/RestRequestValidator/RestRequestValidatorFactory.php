<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator;

use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReader;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator;

class RestRequestValidatorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface
     */
    public function createRestRequestValidator(): RestRequestValidatorInterface
    {
        return new RestRequestValidator($this->createRestRequestConfigurationReader());
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface
     */
    protected function createRestRequestConfigurationReader(): RestRequestValidatorConfigReaderInterface
    {
        return new RestRequestValidatorConfigReader();
    }
}
