<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProduct\Business\Validator;

use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ContentProduct\ContentProductConfig;

class ContentProductAbstractListValidator implements ContentProductAbstractListValidatorInterface
{
    protected const ERROR_MESSAGE_MAX_NUMBER_OF_PRODUCTS = 'There are too many products in the list, please reduce the list size to {number} or fewer.';
    protected const ERROR_MESSAGE_PARAMETER_COUNT = '{number}';

    /**
     * @var \Spryker\Zed\ContentProduct\ContentProductConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ContentProduct\ContentProductConfig $config
     */
    public function __construct(ContentProductConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validate(
        ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
    ): ContentValidationResponseTransfer {
        $contentValidationResponseTransfer = (new ContentValidationResponseTransfer())
            ->setIsSuccess(true);

        $contentParameterMessageTransfer = $this->validateNumberOfProductsConstraint($contentProductAbstractListTermTransfer);

        if ($contentParameterMessageTransfer->getMessages()->count()) {
            $contentValidationResponseTransfer->setIsSuccess(false)
                ->addParameterMessages($contentParameterMessageTransfer);
        }

        return $contentValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentParameterMessageTransfer
     */
    protected function validateNumberOfProductsConstraint(
        ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
    ): ContentParameterMessageTransfer {
        $numberOfProductsInProductAbstractList = count($contentProductAbstractListTermTransfer->getIdProductAbstracts());
        $maxProductsInProductAbstractList = $this->config->getMaxProductsInProductAbstractList();

        if ($numberOfProductsInProductAbstractList > $maxProductsInProductAbstractList) {
            $message = (new MessageTransfer())
                ->setValue(static::ERROR_MESSAGE_MAX_NUMBER_OF_PRODUCTS)
                ->setParameters([static::ERROR_MESSAGE_PARAMETER_COUNT => $maxProductsInProductAbstractList]);

            return (new ContentParameterMessageTransfer())
                ->setParameter(ContentProductAbstractListTermTransfer::ID_PRODUCT_ABSTRACTS)
                ->addMessage($message);
        }

        return new ContentParameterMessageTransfer();
    }
}
