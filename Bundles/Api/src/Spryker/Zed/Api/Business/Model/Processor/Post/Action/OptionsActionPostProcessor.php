<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post\Action;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface;

/**
 * OPTIONS response.
 */
class OptionsActionPostProcessor implements PostProcessorInterface
{

    const HEADER_ALLOW = 'Allow';

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        $action = $apiRequestTransfer->getResourceAction();
        if ($action !== ApiConfig::ACTION_OPTIONS) {
            return $apiResponseTransfer;
        }
        if ($apiResponseTransfer->getCode() !== null) {
            return $apiResponseTransfer;
        }

        $apiResponseTransfer->setCode(ApiConfig::HTTP_CODE_SUCCESS);

        $options = $apiResponseTransfer->getOptions();
        foreach ($options as $key => $value) {
            $options[$key] = strtoupper($value);
        }

        $headers = $apiResponseTransfer->getHeaders();
        $headers[static::HEADER_ALLOW] = implode(', ', $options);
        $apiResponseTransfer->setHeaders($headers);

        return $apiResponseTransfer;
    }

}
