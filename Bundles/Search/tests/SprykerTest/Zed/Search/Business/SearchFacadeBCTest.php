<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Psr\Log\NullLogger;
use Spryker\Zed\Search\Business\SearchFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Facade
 * @group SearchFacadeBCTest
 * Add your own group annotations below this line
 *
 * @deprecated Use `\SprykerTest\Zed\Search\Business\SearchFacadeTest` instead.
 */
class SearchFacadeBCTest extends Unit
{
    public const INDEX_NAME = 'de_search_devtest';

    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteDeletesAnIndex(): void
    {
        $index = $this->tester->haveIndex(static::INDEX_NAME);
        $response = $this->tester->getFacade()->delete();

        $this->assertTrue($response->isOk(), 'Delete response was expected to be true but is false.');
        $this->assertFalse($index->exists(), 'Index was expected to be deleted but still exists.');
    }

    /**
     * @return void
     */
    public function testGetTotalCountReturnsNumberOfDocumentsInAnIndex(): void
    {
        $this->tester->haveDocumentInIndex(static::INDEX_NAME);

        $response = $this->tester->getFacade()->getTotalCount();

        $this->assertSame(1, $response, sprintf('Expected exactly one document but found "%s".', $response));
    }

    /**
     * @return void
     */
    public function testInstallIndexInstallsIndices(): void
    {
        $this->tester->mockConfigMethod('getClassTargetDirectory', codecept_output_dir());
        $this->tester->mockConfigMethod('getJsonIndexDefinitionDirectories', [
            codecept_data_dir('Fixtures/Definition/FinderBC'),
        ]);

        $logger = new NullLogger();
        $this->tester->getFacade()->install($logger);

        $client = $this->tester->getFactory()->getElasticsearchClient();
        $index = $client->getIndex(static::INDEX_NAME);

        $this->assertTrue($index->exists(), 'Index was expected to be installed but was not.');

        $this->tester->getFacade()->delete(static::INDEX_NAME);
    }

    /**
     * @group foobar
     */
    public function testCanMapRawDataToSearchData()
    {
        $inputData = array (
            'label_ids' =>
                array (
                ),
            'product_list_map' =>
                array (
                    'whitelists' =>
                        array (
                            0 => 10,
                        ),
                    'blacklists' =>
                        array (
                            0 => 3,
                        ),
                ),
            'id_product_abstract' => 170,
            'id_image_set' => 384,
            'category_node_ids' =>
                array (
                    0 => 14,
                    1 => 8,
                ),
            'attributes' =>
                array (
                    'processor_frequency' => '1.44 GHz',
                    'processor_cores' => '4',
                    'storage_media' => 'flash',
                    'graphics_adapter' => 'HD Graphics',
                    'brand' => 'HP',
                    'internal_storage_capacity' =>
                        array (
                            0 => '128 GB',
                            1 => '64 GB',
                        ),
                ),
            'name' => 'HP Pro Tablet 608 G1',
            'sku' => '170',
            'url' => '/en/hp-pro-tablet-608-g1-170',
            'locale' => 'en_US',
            'store' => 'DE',
            'abstract_description' => 'Create efficiencies with mobile solutions Tailor your experience to the needs of your business with a keyboard, cases, portable docking station and more to customize your tablet.        Transform your business with this small stylish business tablet equipped with a brilliant, high definition display and the pervasive connectivity to mobilize most any business process. Choose from an expansive choice of accessories for a customized experience. Get business-class security and manageability options on the HP Pro Tablet 608 with HP Client Security, HP Touchpoint Manager and more.    ',
            'concrete_description' => 'Create efficiencies with mobile solutions Tailor your experience to the needs of your business with a keyboard, cases, portable docking station and more to customize your tablet.         Transform your business with this small stylish business tablet equipped with a brilliant, high definition display and the pervasive connectivity to mobilize most any business process. Choose from an expansive choice of accessories for a customized experience. Get business-class security and manageability options on the HP Pro Tablet 608 with HP Client Security, HP Touchpoint Manager and more.    , Create efficiencies with mobile solutions Tailor your experience to the needs of your business with a keyboard, cases, portable docking station and more to customize your tablet.      Transform your business with this small stylish business tablet equipped with a brilliant, high definition display and the pervasive connectivity to mobilize most any business process. Choose from an expansive choice of accessories for a customized experience. Get business-class security and manageability options on the HP Pro Tablet 608 with HP Client Security, HP Touchpoint Manager and more.    ',
            'concrete_skus' => '170_28516206, 170_28549472',
            'concrete_names' => 'HP Pro Tablet 608 G1',
            'type' => 'product_abstract',
            'is_featured' => NULL,
            'is_active' => true,
            'prices' =>
                array (
                    'EUR' =>
                        array (
                            'priceData' => NULL,
                            'GROSS_MODE' =>
                                array (
                                    'DEFAULT' => 6707,
                                ),
                            'NET_MODE' =>
                                array (
                                    'DEFAULT' => 6036,
                                ),
                        ),
                    'CHF' =>
                        array (
                            'priceData' => NULL,
                            'GROSS_MODE' =>
                                array (
                                    'DEFAULT' => 7713,
                                ),
                            'NET_MODE' =>
                                array (
                                    'DEFAULT' => 6941,
                                ),
                        ),
                ),
            'price' => 6707,
            'all_parent_category_ids' =>
                array (
                    0 => 14,
                    1 => 1,
                    2 => 5,
                    3 => 8,
                ),
            'boosted_category_names' =>
                array (
                    14 => 'Variant Showcase',
                    8 => 'Tablets',
                ),
            'category_names' =>
                array (
                    5 => 'Computer',
                ),
            'sorted_categories' =>
                array (
                    14 =>
                        array (
                            'product_order' => 36,
                            'all_node_parents' =>
                                array (
                                    0 => 14,
                                    1 => 1,
                                ),
                        ),
                    8 =>
                        array (
                            'product_order' => 13,
                            'all_node_parents' =>
                                array (
                                    0 => 5,
                                    1 => 8,
                                    2 => 1,
                                ),
                        ),
                ),
            'product_images' =>
                array (
                    0 =>
                        array (
                            'id_product_image' => 384,
                            'external_url_large' => '//images.icecat.biz/img/gallery/28516206_9834842392.jpg',
                            'external_url_small' => '//images.icecat.biz/img/gallery_mediums/28516206_9834842392.jpg',
                            'product_image_key' => 'product_image_384',
                            'created_at' => '2019-11-01 11:19:13.754523',
                            'updated_at' => '2019-11-01 11:19:13.754523',
                            'id_product_image_set_to_product_image' => 384,
                            'fk_product_image' => 384,
                            'fk_product_image_set' => 384,
                            'sort_order' => 0,
                        ),
                ),
            'average_rating' => NULL,
            'review_count' => NULL,
        );
        $expected = array (
            'store' => 'DE',
            'locale' => 'en_US',
            'type' => 'product_abstract',
            'is-active' => true,
            'search-result-data' =>
                array (
                    'id_product_abstract' => 170,
                    'abstract_sku' => '170',
                    'abstract_name' => 'HP Pro Tablet 608 G1',
                    'url' => '/en/hp-pro-tablet-608-g1-170',
                    'type' => 'product_abstract',
                    'price' => 6707,
                    'prices' =>
                        array (
                            'EUR' =>
                                array (
                                    'priceData' => NULL,
                                    'GROSS_MODE' =>
                                        array (
                                            'DEFAULT' => 6707,
                                        ),
                                    'NET_MODE' =>
                                        array (
                                            'DEFAULT' => 6036,
                                        ),
                                ),
                            'CHF' =>
                                array (
                                    'priceData' => NULL,
                                    'GROSS_MODE' =>
                                        array (
                                            'DEFAULT' => 7713,
                                        ),
                                    'NET_MODE' =>
                                        array (
                                            'DEFAULT' => 6941,
                                        ),
                                ),
                        ),
                    'images' =>
                        array (
                            0 =>
                                array (
                                    'id_product_image' => 384,
                                    'external_url_large' => '//images.icecat.biz/img/gallery/28516206_9834842392.jpg',
                                    'external_url_small' => '//images.icecat.biz/img/gallery_mediums/28516206_9834842392.jpg',
                                    'product_image_key' => 'product_image_384',
                                    'created_at' => '2019-11-01 11:19:13.754523',
                                    'updated_at' => '2019-11-01 11:19:13.754523',
                                    'id_product_image_set_to_product_image' => 384,
                                    'fk_product_image' => 384,
                                    'fk_product_image_set' => 384,
                                    'sort_order' => 0,
                                ),
                        ),
                    'id_product_labels' =>
                        array (
                        ),
                ),
            'full-text-boosted' =>
                array (
                    0 => 'HP Pro Tablet 608 G1',
                    1 => '170',
                    2 => 'Variant Showcase',
                    3 => 'Tablets',
                ),
            'full-text' =>
                array (
                    0 => 'HP Pro Tablet 608 G1',
                    1 => '170_28516206, 170_28549472',
                    2 => 'Create efficiencies with mobile solutions Tailor your experience to the needs of your business with a keyboard, cases, portable docking station and more to customize your tablet.        Transform your business with this small stylish business tablet equipped with a brilliant, high definition display and the pervasive connectivity to mobilize most any business process. Choose from an expansive choice of accessories for a customized experience. Get business-class security and manageability options on the HP Pro Tablet 608 with HP Client Security, HP Touchpoint Manager and more.    ',
                    3 => 'Create efficiencies with mobile solutions Tailor your experience to the needs of your business with a keyboard, cases, portable docking station and more to customize your tablet.         Transform your business with this small stylish business tablet equipped with a brilliant, high definition display and the pervasive connectivity to mobilize most any business process. Choose from an expansive choice of accessories for a customized experience. Get business-class security and manageability options on the HP Pro Tablet 608 with HP Client Security, HP Touchpoint Manager and more.    , Create efficiencies with mobile solutions Tailor your experience to the needs of your business with a keyboard, cases, portable docking station and more to customize your tablet.      Transform your business with this small stylish business tablet equipped with a brilliant, high definition display and the pervasive connectivity to mobilize most any business process. Choose from an expansive choice of accessories for a customized experience. Get business-class security and manageability options on the HP Pro Tablet 608 with HP Client Security, HP Touchpoint Manager and more.    ',
                    4 => 'Computer',
                    5 => 'HP',
                ),
            'suggestion-terms' =>
                array (
                    0 => 'HP Pro Tablet 608 G1',
                    1 => 'HP',
                ),
            'completion-terms' =>
                array (
                    0 => 'HP Pro Tablet 608 G1',
                    1 => 'HP',
                ),
            'string-sort' =>
                array (
                    'name' => 'HP Pro Tablet 608 G1',
                ),
            'integer-sort' =>
                array (
                    'price' => 6707,
                    'price-DEFAULT-EUR-NET_MODE' => 6036,
                    'price-DEFAULT-EUR-GROSS_MODE' => 6707,
                    'price-DEFAULT-CHF-NET_MODE' => 6941,
                    'price-DEFAULT-CHF-GROSS_MODE' => 7713,
                    'category:14' => 36,
                    'category:1' => 13,
                    'category:8' => 13,
                    'category:5' => 13,
                ),
            'integer-facet' =>
                array (
                    0 =>
                        array (
                            'facet-name' => 'price',
                            'facet-value' =>
                                array (
                                    0 => 6707,
                                ),
                        ),
                    1 =>
                        array (
                            'facet-name' => 'price-DEFAULT-EUR-NET_MODE',
                            'facet-value' =>
                                array (
                                    0 => 6036,
                                ),
                        ),
                    2 =>
                        array (
                            'facet-name' => 'price-DEFAULT-EUR-GROSS_MODE',
                            'facet-value' =>
                                array (
                                    0 => 6707,
                                ),
                        ),
                    3 =>
                        array (
                            'facet-name' => 'price-DEFAULT-CHF-NET_MODE',
                            'facet-value' =>
                                array (
                                    0 => 6941,
                                ),
                        ),
                    4 =>
                        array (
                            'facet-name' => 'price-DEFAULT-CHF-GROSS_MODE',
                            'facet-value' =>
                                array (
                                    0 => 7713,
                                ),
                        ),
                ),
            'category' =>
                array (
                    'all-parents' =>
                        array (
                            0 => 14,
                            1 => 1,
                            2 => 5,
                            3 => 8,
                        ),
                    'direct-parents' =>
                        array (
                            0 => 14,
                            1 => 8,
                        ),
                ),
            'product-lists' =>
                array (
                    'whitelists' =>
                        array (
                            0 => 10,
                        ),
                    'blacklists' =>
                        array (
                            0 => 3,
                        ),
                ),
            'string-facet' =>
                array (
                    0 =>
                        array (
                            'facet-name' => 'brand',
                            'facet-value' =>
                                array (
                                    0 => 'HP',
                                ),
                        ),
                ),
        );
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName('en_US');
        $facade = new SearchFacade();
        $result = $facade->transformPageMapToDocumentByMapperName($inputData, $localeTransfer, 'product_abstract');

        $this->assertSame($expected, $result);
    }
}
