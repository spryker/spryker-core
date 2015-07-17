<?php

namespace SprykerFeature\Zed\Distributor\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Distributor\Business\Exception\ItemTypeDoesNotExistException;

interface ItemWriterInterface
{

    /**
     * @param string $itemType
     * @param int $idItem
     *
     * @throws ItemTypeDoesNotExistException
     * @throws PropelException
     */
    public function touchItem($itemType, $idItem);

}
