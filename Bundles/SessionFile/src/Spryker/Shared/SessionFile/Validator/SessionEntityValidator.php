<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Validator;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilderInterface;
use Spryker\Shared\SessionFile\Hasher\HasherInterface;

class SessionEntityValidator implements SessionEntityValidatorInterface
{
    /**
     * @var \Spryker\Shared\SessionFile\Hasher\HasherInterface
     */
    protected HasherInterface $hasher;

    /**
     * @var \Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilderInterface
     */
    protected SessionEntityFileNameBuilderInterface $sessionEntityFileNameBuilder;

    /**
     * @param \Spryker\Shared\SessionFile\Hasher\HasherInterface $hasher
     * @param \Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilderInterface $sessionEntityFileNameBuilder
     */
    public function __construct(HasherInterface $hasher, SessionEntityFileNameBuilderInterface $sessionEntityFileNameBuilder)
    {
        $this->hasher = $hasher;
        $this->sessionEntityFileNameBuilder = $sessionEntityFileNameBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function validate(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        $sessionEntityResponseTransfer = (new SessionEntityResponseTransfer())
            ->setIsSuccessfull(true);

        $fileName = $this->sessionEntityFileNameBuilder->build($sessionEntityRequestTransfer);
        if (!file_exists($fileName)) {
            return $sessionEntityResponseTransfer;
        }

        $hash = file_get_contents($fileName);
        if ($hash === false) {
            return $sessionEntityResponseTransfer;
        }

        $isSessionEntityValid = $this->hasher->validate(
            $sessionEntityRequestTransfer->getIdSessionOrFail(),
            $hash,
        );

        return $sessionEntityResponseTransfer->setIsSuccessfull($isSessionEntityValid);
    }
}
