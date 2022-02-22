<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorEntityManagerInterface;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;

class ItemMetadataSaver implements ItemMetadataSaverInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorEntityManagerInterface $entityManager
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface $repository
     */
    public function __construct(
        SalesProductConnectorToUtilEncodingInterface $utilEncodingService,
        SalesProductConnectorEntityManagerInterface $entityManager,
        SalesProductConnectorRepositoryInterface $repository
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveItemsMetadata(QuoteTransfer $quoteTransfer)
    {
        $this->entityManager->saveItemsMetadata(
            $quoteTransfer,
            $this->repository->getSupperAttributesGroupedByIdItem($quoteTransfer),
        );
    }
}
