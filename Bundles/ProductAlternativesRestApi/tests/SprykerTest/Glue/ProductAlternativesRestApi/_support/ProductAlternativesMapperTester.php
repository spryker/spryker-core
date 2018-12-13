<?php

namespace SprykerTest\Glue\ProductAlternativesRestApi;

use Codeception\Actor;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductAlternativesMapperTester extends Actor
{
    use _generated\ProductAlternativesMapperTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return array
     */
    public function hasProductConcreteStorageData(): array
    {
        return [
            'id_product_concrete' => 185,
            'id_product_abstract' => 134,
            'attributes' => [
                'form_factor' => 'clamshell',
                'processor_boost_frequency' => '3 GHz',
                'processor_threads' => '4',
                'brand' => 'Acer',
                'color' => 'White',
                'os_installed' => 'Windows 8.1 Pro',
            ],
            'name' => 'Acer Aspire S7',
            'sku' => '134_26145012',
            'url' => '/en/acer-aspire-s7-134',
            'description' => 'MORE power The new Aspire S7 (the S7-393 to be precise) comes with your choice of Intel\'s new 5th Generation Core™ processors. These deliver improved overall performance and graphics, while also using less power. That\'s why the new Aspire S7 is even more powerful, and has a longer battery life. You can now go even longer between charge times. The new Aspire S7’s improved hardware doesn’t just make it more powerful, it also helps it score even higher in battery-life tests. With up to 8.5 hours2 of on-the-road power, you can go all day and do it all. With cutting-edge 802.11ac wireless technology firing on dual channels, the new Aspire S7 transmits and receives airborne data up to three times faster than the average laptop does3. Download and upload movies (and do pretty much anything on the web) at breathtaking speeds. The new Aspire S7 is beautifully thin and delightfully light. With a thickness of only 12.9 mm, and a weight of only 1.3 kg, this slender-but-strong beauty disappears into your carry bag without a whisper. It\'s so easy to carry; you\'ll forget it\'s there.',
            'meta_title' => 'Acer Aspire S7',
            'meta_keywords' => 'Acer,Entertainment Electronics',
            'meta_description' => 'MORE power The new Aspire S7 (the S7-393 to be precise) comes with your choice of Intel\'s new 5th Generation Core™ processors. These deliver improved overa',
            'super_attributes_definition' => [
                'form_factor',
                'color',
                'os_installed',
            ],
            'color_code' => null,
            '_timestamp' => 1544520119.1892,
        ];
    }

    /**
     * @return array
     */
    public function hasProductAbstractStorageData(): array
    {
        return [
            'id_product_abstract' => 134,
            'attributes' => [
                'form_factor' => 'clamshell',
                'processor_boost_frequency' => '3 GHz',
                'processor_threads' => '4',
                'brand' => 'Acer',
                'color' => 'White',
            ],
            'name' => 'Acer Aspire S7',
            'sku' => '134',
            'url' => '/en/acer-aspire-s7-134',
            'description' => 'MORE power The new Aspire S7 (the S7-393 to be precise) comes with your choice of Intel\'s new 5th Generation Core™ processors. These deliver improved overall performance and graphics, while also using less power. That\'s why the new Aspire S7 is even more powerful, and has a longer battery life. You can now go even longer between charge times. The new Aspire S7’s improved hardware doesn’t just make it more powerful, it also helps it score even higher in battery-life tests. With up to 8.5 hours2 of on-the-road power, you can go all day and do it all. With cutting-edge 802.11ac wireless technology firing on dual channels, the new Aspire S7 transmits and receives airborne data up to three times faster than the average laptop does3. Download and upload movies (and do pretty much anything on the web) at breathtaking speeds. The new Aspire S7 is beautifully thin and delightfully light. With a thickness of only 12.9 mm, and a weight of only 1.3 kg, this slender-but-strong beauty disappears into your carry bag without a whisper. It\'s so easy to carry; you\'ll forget it\'s there.',
            'meta_title' => 'Acer Aspire S7',
            'meta_keywords' => 'Acer,Entertainment Electronics',
            'meta_description' => 'MORE power The new Aspire S7 (the S7-393 to be precise) comes with your choice of Intel\'s new 5th Generation Core™ processors. These deliver improved overa',
            'super_attributes_definition' => [
                'form_factor',
                'color',
            ],
            'attribute_map' => [
                'attribute_variants' => [
                    'os_installed:Windows 10 Home' => [
                        'id_product_concrete' => 184,
                    ],
                    'os_installed:Windows 8.1 Pro' => [
                        'id_product_concrete' => 185,
                    ],
                ],
                'super_attributes' => [
                    'os_installed' => [
                        'Windows 10 Home',
                        'Windows 8.1 Pro',
                    ],
                ],
                'product_concrete_ids' => [
                    '134_29759322' => 184,
                    '134_26145012' => 186,
                ],
            ],
            'color_code' => '#FFFFFF',
            '_timestamp' => 1544531244.863,
        ];
    }
}
