<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization;

use Codeception\Actor;
use ReflectionClass;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class SynchronizationBusinessTester extends Actor
{
    use _generated\SynchronizationBusinessTesterActions;

    /**
     * @param string $className
     * @param string $propertyName
     *
     * @return void
     */
    public function clearStaticVariable(string $className, string $propertyName): void
    {
        $reflectionResolver = new ReflectionClass($className);
        $reflectionProperty = $reflectionResolver->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }

    /**
     * @param string $className
     * @param string $propertyName
     *
     * @return array<mixed>
     */
    public function getStaticVariable(string $className, string $propertyName): array
    {
        $reflectionResolver = new ReflectionClass($className);
        $reflectionProperty = $reflectionResolver->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue();
    }

    /**
     * @param string $destinationType
     * @param string $queueName
     * @param string $operationType
     *
     * @return array<mixed>
     */
    public function createFakeSynchronizationMessage(
        string $destinationType,
        string $queueName,
        string $operationType
    ): array {
        return [
            'data' => [
                'key' => 'product_concrete:en_us:321',
                'value' => [
                    'id_product_abstract' => 223,
                    'id_product_concrete' => 321,
                    'name' => 'HDMI cable (1.5m)',
                    'sku' => 'cable-hdmi-1-1',
                    '_timestamp' => 1722247340.708308,
                ],
                'resource' => 'product_concrete',
                'store' => '',
                'params' => [],
            ],
            'fallback_queue_name' => $queueName,
            'sync_destination_type' => $destinationType,
            'operation_type' => $operationType,
            'locale' => 'en_US',
            'resource' => 'product_concrete',
            'fallback_queue_message' => [
                'body' => '{"write":{"key":"product_concrete:en_us:321","value":{"merchant_reference":null,"id_product_abstract":223,"id_product_concrete":321,"attributes":{"packaging_unit":"Ring"},"name":"HDMI cable (1.5m)","sku":"cable-hdmi-1-1","url":"\\/en\\/hdmi-cable-223","description":"Enjoy clear, crisp, immediate connectivity with the High-Speed HDMI Cable. This quality High-Definition Multimedia Interface (HDMI) cable allows you to connect a wide variety of devices in the realms of home entertainment, computing, gaming, and more to your HDTV, projector, or monitor. Perfect for those that interact with multiple platforms and devices, you can rely on strong performance and playback delivery when it comes to your digital experience.","meta_title":null,"meta_keywords":null,"meta_description":null,"super_attributes_definition":["packaging_unit"],"color_code":null,"_timestamp":1722247340.708308},"resource":"product_concrete","store":"","params":[]}}',
                'routing_key' => null,
                'headers' => [],
                'store_name' => null,
                'locale' => null,
                'queue_pool_name' => 'synchronizationPool',
            ],
        ];
    }
}
