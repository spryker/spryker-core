<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Transfer\Business\TransferBusinessFactory getFactory()
 */
class TransferFacade extends AbstractFacade implements TransferFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateTransferObjects(LoggerInterface $messenger)
    {
        $this->getFactory()->createTransferGenerator($messenger)->execute();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateEntityTransferObjects(LoggerInterface $messenger)
    {
        $this->getFactory()->createEntityTransferGenerator($messenger)->execute();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateDataBuilders(LoggerInterface $messenger)
    {
        $this->getFactory()->createDataBuilderGenerator($messenger)->execute();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link deleteGeneratedDataTransferObjects()} instead to generate data transfers.
     *   Use {@link deleteGeneratedEntityTransferObjects()} instead to generate entity transfers.
     *
     * @return void
     */
    public function deleteGeneratedTransferObjects()
    {
        $this->getFactory()->createTransferGeneratedDirectory()->clear();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteGeneratedDataTransferObjects(): void
    {
        $this->getFactory()->createDataTransferGeneratedDirectory()->clear();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteGeneratedEntityTransferObjects(): void
    {
        $this->getFactory()->createEntityTransferGeneratedDirectory()->clear();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteGeneratedDataBuilderObjects()
    {
        $this->getFactory()->createDataBuilderGeneratedDirectory()->clear();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    public function validateTransferObjects(LoggerInterface $messenger, array $options)
    {
        return $this->getFactory()->createValidator($messenger)->validate($options);
    }
}
