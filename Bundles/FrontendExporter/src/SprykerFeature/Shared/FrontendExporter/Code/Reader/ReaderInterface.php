<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\FrontendExporter\Code\Reader;

/**
 * Interface ReaderInterface
 *
 * @package SprykerFeature\Zed\FrontendExporter\Exporter\Reader
 */
interface ReaderInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function read($key);

    /**
     * @return string
     */
    public function getName();
}
 