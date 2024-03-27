<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;

class RequestNoteLengthValidatorRule implements MerchantRelationValidatorRuleInterface
{
    /**
     * @var int
     */
    protected const REQUEST_NOTE_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const REQUEST_NOTE_MAX_LENGTH = 5000;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MIN = '%min%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MAX = '%max%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_NOTE_WRONG_LENGTH = 'merchant_relation_request.validation.request_note_wrong_length';

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(ErrorAdderInterface $errorAdder)
    {
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequestTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantRelationRequestTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($merchantRelationRequestTransfers as $entityIdentifier => $merchantRelationRequestTransfer) {
            if (!$merchantRelationRequestTransfer->getRequestNote()) {
                continue;
            }

            if (!$this->isRequestNoteLengthValid($merchantRelationRequestTransfer->getRequestNoteOrFail())) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_REQUEST_NOTE_WRONG_LENGTH,
                    [
                        static::GLOSSARY_KEY_PARAMETER_MIN => static::REQUEST_NOTE_MIN_LENGTH,
                        static::GLOSSARY_KEY_PARAMETER_MAX => static::REQUEST_NOTE_MAX_LENGTH,
                    ],
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param string $requestNote
     *
     * @return bool
     */
    protected function isRequestNoteLengthValid(string $requestNote): bool
    {
        return mb_strlen($requestNote) >= static::REQUEST_NOTE_MIN_LENGTH
            && mb_strlen($requestNote) <= static::REQUEST_NOTE_MAX_LENGTH;
    }
}
