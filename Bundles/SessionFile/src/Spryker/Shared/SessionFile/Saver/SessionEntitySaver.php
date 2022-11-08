<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Saver;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Shared\SessionFile\Builder\SessionEntityFileNameBuilderInterface;
use Spryker\Shared\SessionFile\Hasher\HasherInterface;

class SessionEntitySaver implements SessionEntitySaverInterface
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
    public function save(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        $sessionEntitySaveResult = file_put_contents(
            $this->sessionEntityFileNameBuilder->build($sessionEntityRequestTransfer),
            $this->hasher->encrypt($sessionEntityRequestTransfer->getIdSessionOrFail()),
        );

        return (new SessionEntityResponseTransfer())
            ->setIsSuccessfull((bool)$sessionEntitySaveResult);
    }
}
