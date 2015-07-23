<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\ReferenceGenerator;

interface RandomNumberGeneratorInterface
{
    /**
     * @return int
     */
    public function generate();
}
