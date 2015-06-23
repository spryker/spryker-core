<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Shared\SearchPage\Dependency\PageElementInterface;

interface PageElementWriterInterface
{
    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     * @throws PropelException
     */
    public function createPageElement(PageElementInterface $pageElement);

    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     * @throws PropelException
     */
    public function updatePageElement(PageElementInterface $pageElement);

    /**
     * @param PageElementInterface $pageElement
     *
     * @return int
     * @throws PropelException
     */
    public function deletePageElement(PageElementInterface $pageElement);
}