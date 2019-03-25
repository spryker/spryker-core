<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProduct\Business\Validator;

use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ContentProduct\ContentProductConfig;

class ContentProductAbstractListValidator implements ContentProductAbstractListValidatorInterface
{
    protected const ERROR_MESSAGE_MAX_NUMBER_OF_PRODUCTS = 'The number of products is too big, max {number}.';
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
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTransfer $contentProductAbstractListTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validate(
        ContentProductAbstractListTransfer $contentProductAbstractListTransfer
    ): ContentValidationResponseTransfer {
        $contentValidationResponseTransfer = (new ContentValidationResponseTransfer())
            ->setIsSuccess(true);

        $contentParameterMessageTransfer = $this->checkNumberOfProducts($contentProductAbstractListTransfer);

        if ($contentParameterMessageTransfer->getMessages()->count()) {
            $contentValidationResponseTransfer->setIsSuccess(false)
                ->addParameterMessages($contentParameterMessageTransfer);
        }

        return $contentValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTransfer $contentProductAbstractListTransfer
     *
     * @return \Generated\Shared\Transfer\ContentParameterMessageTransfer
     */
    protected function checkNumberOfProducts(ContentProductAbstractListTransfer $contentProductAbstractListTransfer): ContentParameterMessageTransfer
    {
        if (count($contentProductAbstractListTransfer->getIdProductAbstracts()) > $this->config->getMaxProductsInProductAbstractList()) {
            $message = (new MessageTransfer())->setValue(static::ERROR_MESSAGE_MAX_NUMBER_OF_PRODUCTS)
                ->setParameters([static::ERROR_MESSAGE_PARAMETER_COUNT => $this->config->getMaxProductsInProductAbstractList()]);

            return (new ContentParameterMessageTransfer())->setParameter(ContentProductAbstractListTransfer::ID_PRODUCT_ABSTRACTS)
                ->addMessages($message);
        }

        return new ContentParameterMessageTransfer();
    }
}
