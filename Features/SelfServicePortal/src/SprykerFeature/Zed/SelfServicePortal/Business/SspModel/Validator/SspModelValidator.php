<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspModelValidator implements SspModelValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_NAME_EMPTY = 'Model name is required.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_NAME_TOO_LONG = 'Model name cannot be longer than %s characters.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CODE_TOO_LONG = 'Model code cannot be longer than %s characters.';

    public function __construct(protected SelfServicePortalConfig $selfServicePortalConfig)
    {
    }

    public function validateModelTransfer(
        SspModelTransfer $sspModelTransfer,
        SspModelCollectionResponseTransfer $sspModelCollectionResponseTransfer
    ): bool {
        $validationErrors = new ArrayObject();

        $this->validateName($sspModelTransfer, $validationErrors);
        $this->validateCode($sspModelTransfer, $validationErrors);

        if ($validationErrors->count() === 0) {
            return true;
        }

        foreach ($validationErrors as $validationError) {
            $sspModelCollectionResponseTransfer->addError($validationError);
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer> $validationErrors
     *
     * @return void
     */
    protected function validateName(SspModelTransfer $sspModelTransfer, ArrayObject $validationErrors): void
    {
        $name = $sspModelTransfer->getName();

        if (!$name) {
            $validationErrors->append(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_NAME_EMPTY),
            );

            return;
        }

        if (mb_strlen($name) > $this->selfServicePortalConfig->getSspModelNameMaxLength()) {
            $validationErrors->append(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_MESSAGE_NAME_TOO_LONG, $this->selfServicePortalConfig->getSspModelNameMaxLength()))
                    ->setParameters([
                        'limit' => $this->selfServicePortalConfig->getSspModelNameMaxLength(),
                    ]),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer> $validationErrors
     *
     * @return void
     */
    protected function validateCode(SspModelTransfer $sspModelTransfer, ArrayObject $validationErrors): void
    {
        $code = $sspModelTransfer->getCode();

        if ($code && mb_strlen($code) > $this->selfServicePortalConfig->getSspModelCodeMaxLength()) {
            $validationErrors->append(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_MESSAGE_CODE_TOO_LONG, $this->selfServicePortalConfig->getSspModelCodeMaxLength()))
                    ->setParameters([
                        'limit' => $this->selfServicePortalConfig->getSspModelCodeMaxLength(),
                    ]),
            );
        }
    }
}
