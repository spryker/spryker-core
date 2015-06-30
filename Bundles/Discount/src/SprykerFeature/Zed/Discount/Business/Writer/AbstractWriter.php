<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;

/**
 * Class AbstractCrudManager
 * @package SprykerFeature\Zed\Discount\Business\Model
 */
abstract class AbstractWriter
{
    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return DiscountQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->locator->discount()->queryContainer();
    }
}
