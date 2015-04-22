<?php

namespace SprykerFeature\Shared\Customer\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class AddressCollection extends AbstractTransferCollection
{
    /**
     * @var string
     */
    protected $transferObjectClass = 'Customer\\Address';
}
