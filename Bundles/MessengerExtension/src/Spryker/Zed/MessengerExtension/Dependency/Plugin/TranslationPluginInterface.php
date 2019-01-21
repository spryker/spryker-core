<?php
/**
 * Created by PhpStorm.
 * User: devromans
 * Date: 2019-01-18
 * Time: 17:13
 */

namespace Spryker\Zed\MessengerExtension\Dependency\Plugin;

interface TranslationPluginInterface
{
    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @api
     *
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    public function translate($keyName, array $data = []);
}
