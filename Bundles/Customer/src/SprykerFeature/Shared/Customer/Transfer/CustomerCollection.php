<?php

namespace SprykerFeature\Shared\Customer\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class CustomerCollection extends AbstractTransferCollection
{
    /**
     * @var string
     */
    protected $transferObjectClass = 'Customer\\Customer';
}
