<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Validator\Request;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Symfony\Component\HttpFoundation\Response;

class FilterRequestValidator implements RequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        if (count($glueRequestTransfer->getFilters()) === 0) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        foreach ($glueRequestTransfer->getFilters() as $filter) {
            if ($filter->getField() === null) {
                return (new GlueRequestValidationTransfer())
                    ->setIsValid(false)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->addError($this->createUnsupportedFilterFormatGlueError());
            }
        }

        return (new GlueRequestValidationTransfer())->setIsValid(true);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createUnsupportedFilterFormatGlueError(): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(GlueJsonApiConventionConfig::ERROR_CODE_UNSUPPORTED_FILTER_FORMAT)
            ->setMessage(GlueJsonApiConventionConfig::ERROR_MESSAGE_UNSUPPORTED_FILTER_FORMAT);
    }
}
