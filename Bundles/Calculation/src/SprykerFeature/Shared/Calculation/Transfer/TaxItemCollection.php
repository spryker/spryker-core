<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxItemInterface;
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
