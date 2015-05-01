<?php
/**
 * Created by PhpStorm.
 * User: trosenstock
 * Date: 27.02.15
 * Time: 15:54
 */
namespace SprykerFeature\Zed\CategoryExporter\Business\Exploder;


/**
 * Class GroupedNodeExploder
 * @package SprykerFeature\Zed\CategoryExporter\Business\Exploder
 */
interface GroupedNodeExploderInterface
{
    /**
     * @param array $data
     * @param string $idsField
     * @param string $namesField
     * @param string $urlField
     * @return array
     */
    public function explodeGroupedNodes(array $data, $idsField, $namesField, $urlField);
}
