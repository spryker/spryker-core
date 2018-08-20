<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface;
use Symfony\Component\HttpFoundation\Request;

class RestRequestValidator implements RestRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface
     */
    protected $configReader;

    /**
     * @param \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReaderInterface $configReader
     */
    public function __construct(RestRequestValidatorConfigReaderInterface $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $this->configReader->getValidationConfiguration($restRequest->getResource()->getType());

        return null;
    }
}
