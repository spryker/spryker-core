<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Reader;

use Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface;

class SalesOrderAmendmentReader implements SalesOrderAmendmentReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface
     */
    protected SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository;

    /**
     * @var list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentExpanderPluginInterface>
     */
    protected array $salesOrderAmendmentExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentExpanderPluginInterface> $salesOrderAmendmentExpanderPlugins
     */
    public function __construct(
        SalesOrderAmendmentRepositoryInterface $salesOrderAmendmentRepository,
        array $salesOrderAmendmentExpanderPlugins
    ) {
        $this->salesOrderAmendmentRepository = $salesOrderAmendmentRepository;
        $this->salesOrderAmendmentExpanderPlugins = $salesOrderAmendmentExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer
     */
    public function getSalesOrderAmendmentCollection(
        SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
    ): SalesOrderAmendmentCollectionTransfer {
        $salesOrderAmendmentCollectionTransfer = $this->salesOrderAmendmentRepository
            ->getSalesOrderAmendmentCollection($salesOrderAmendmentCriteriaTransfer);

        return $this->executeSalesOrderAmendmentExpanderPlugins($salesOrderAmendmentCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer $salesOrderAmendmentCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer
     */
    protected function executeSalesOrderAmendmentExpanderPlugins(
        SalesOrderAmendmentCollectionTransfer $salesOrderAmendmentCollectionTransfer
    ): SalesOrderAmendmentCollectionTransfer {
        foreach ($this->salesOrderAmendmentExpanderPlugins as $salesOrderAmendmentExpanderPlugin) {
            $salesOrderAmendmentCollectionTransfer = $salesOrderAmendmentExpanderPlugin->expand(
                $salesOrderAmendmentCollectionTransfer,
            );
        }

        return $salesOrderAmendmentCollectionTransfer;
    }
}
