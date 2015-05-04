<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyTaxItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyTaxItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

/**
 *
 */
class TaxItemCollection extends AbstractTransferCollection implements TaxItemCollectionInterface
{
    /**
     * @var TaxItemInterface
     */
    protected $transferObjectClass = 'Calculation\\TaxItem';
}
