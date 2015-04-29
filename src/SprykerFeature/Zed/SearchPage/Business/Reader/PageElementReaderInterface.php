<?php

namespace SprykerFeature\Zed\SearchPage\Business\Reader;

use SprykerFeature\Zed\SearchPage\Business\Exception\PageElementDoesNotExistException;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageElement;

interface PageElementReaderInterface
{

    /**
     * @param int $idPageElement
     *
     * @return SpySearchPageElement
     * @throws PageElementDoesNotExistException
     */
    public function getPageElementById($idPageElement);
}
