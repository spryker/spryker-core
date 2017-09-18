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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteGeneratedTransferObjects()
    {
        $this->getFactory()->createTransferGeneratedDirectory()->clear();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     * @param array $options
     *
     * @return bool
     */
    public function validateTransferObjects(LoggerInterface $messenger, array $options)
    {
        return $this->getFactory()->createValidator($messenger)->validate($options);
    }

}
