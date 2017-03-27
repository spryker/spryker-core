<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business;

use Psr\Log\LoggerInterface;

interface TransferFacadeInterface
{

    /**
     * Specification:
     * - Loads all *transfer.xml definitions
     * - Generates transfer objects
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateTransferObjects(LoggerInterface $messenger);

    /**
     * Specification:
     * - Deletes all generated transfer objects
     *
     * @api
     *
     * @return void
     */
    public function deleteGeneratedTransferObjects();

    /**
     * Specification:
     * - Loads all *transfer.xml definitions
     * - Generates transfer data builder objects
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateDataBuilders(LoggerInterface $messenger);

    /**
     * Specification:
     * - Deletes all generated transfer data builder objects
     *
     * @api
     *
     * @return void
     */
    public function deleteGeneratedDataBuilderObjects();

    /**
     * Specification:
     * - Validates all generated transfer objects
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     * @param array $options
     *
     * @return bool
     */
    public function validateTransferObjects(LoggerInterface $messenger, array $options);

}
