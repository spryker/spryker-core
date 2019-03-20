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
    protected const PARAMETER_SKUS = 'skus';
    protected const ERROR_MESSAGE_NUMBER_OF_PRODUCTS = 'Number of products is too long, max {count}, please check row with key:"{key}", column:"{column}"';
    protected const ERROR_MESSAGE_PARAMETER_COUNT = '{count}';
    protected const TYPE_ERROR_MESSAGE = 'error';

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

        $сontentParameterMessageTransfer = $this->checkNumberOfProducts(
            $contentProductAbstractListTransfer->getIdProductAbstracts()
        );

        if ($сontentParameterMessageTransfer->getMessages()->count()) {
            $contentValidationResponseTransfer->setIsSuccess(false)
                ->addParameterMessages($сontentParameterMessageTransfer);
        }

        return $contentValidationResponseTransfer;
    }

    /**
     * @param int[] $idProductAbstracts
     *
     * @return \Generated\Shared\Transfer\ContentParameterMessageTransfer
     */
    protected function checkNumberOfProducts(array $idProductAbstracts): ContentParameterMessageTransfer
    {
        if (count($idProductAbstracts) > ContentProductConfig::MAX_NUMBER_PRODUCTS_IN_PRODUCT_ABSTRACT_LIST) {
            $message = (new MessageTransfer())->setType(static::TYPE_ERROR_MESSAGE)
                ->setValue(static::ERROR_MESSAGE_NUMBER_OF_PRODUCTS)
                ->setParameters([static::ERROR_MESSAGE_PARAMETER_COUNT => ContentProductConfig::MAX_NUMBER_PRODUCTS_IN_PRODUCT_ABSTRACT_LIST]);

            return (new ContentParameterMessageTransfer())->setParameter(static::PARAMETER_SKUS)
                ->addMessages($message);
        }

        return new ContentParameterMessageTransfer();
    }
}
