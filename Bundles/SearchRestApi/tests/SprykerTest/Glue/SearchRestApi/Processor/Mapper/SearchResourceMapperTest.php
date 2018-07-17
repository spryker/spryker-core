<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SearchRestApi\Processor\Mapper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group SearchRestApi
 * @group Processor
 * @group Mapper
 * @group SearchResourceMapperTest
 * Add your own group annotations below this line
 */
class SearchResourceMapperTest extends AbstractMapperTest
{
    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithNotEmptyAttributesData()
    {
        $this->restSearchAttributesTransfer = $this
            ->searchResourceMapper
            ->mapSearchResponseAttributesTransferToRestResponse($this->mockRestSearchResponseTransfer())
            ->getAttributes();

        $this->assertEquals(3, $this->restSearchAttributesTransfer->getProducts()->count());
        $this->assertEquals("cameras", $this->restSearchAttributesTransfer->getSpellingSuggestion());

        $this->assertEquals("Toshiba CAMILEO S20", $this->restSearchAttributesTransfer->getProducts()[0]->getAbstractName());
        $this->assertEquals(19568, $this->restSearchAttributesTransfer->getProducts()[0]->getPrice());
        $this->assertEquals("209", $this->restSearchAttributesTransfer->getProducts()[0]->getAbstractSku());
        $this->assertEquals(19568, $this->restSearchAttributesTransfer->getProducts()[0]->getPrices()['DEFAULT']);
        $this->assertArrayNotHasKey("id_product_abstract", $this->restSearchAttributesTransfer->getProducts()[0]);
        $this->assertArrayNotHasKey("id_product_labels", $this->restSearchAttributesTransfer->getProducts()[0]);

        $this->assertArrayNotHasKey("fk_product_image_set", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);
        $this->assertArrayNotHasKey("id_product_image", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);
        $this->assertArrayNotHasKey("id_product_image_set_to_product_image", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);
        $this->assertArrayNotHasKey("fk_product_image", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]);

        $this->assertEquals("//images.icecat.biz/img/norm/medium/15743_12554247-9579.jpg", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]['externalUrlSmall']);
        $this->assertEquals("//images.icecat.biz/img/norm/high/15743_12554247-9579.jpg", $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]['externalUrlLarge']);
        $this->assertEquals(0, $this->restSearchAttributesTransfer->getProducts()[0]->getImages()[0]['sortOrder']);

        $this->assertEquals("name_asc", $this->restSearchAttributesTransfer->getSort()->getCurrentSortOrder());
        $this->assertEquals("1", $this->restSearchAttributesTransfer->getSort()->getCurrentSortParam());
        $this->assertArraySubset($this->restSearchAttributesTransfer->getSort()->getSortParamNames(), ["rating", "name_asc", "name_desc", "price_asc", "price_desc"]);
        $this->assertArraySubset(["rating", "name_asc", "name_desc", "price_asc", "price_desc"], $this->restSearchAttributesTransfer->getSort()->getSortParamNames());

        $this->assertEquals(1, $this->restSearchAttributesTransfer->getPagination()->getCurrentPage());
        $this->assertEquals(12, $this->restSearchAttributesTransfer->getPagination()->getCurrentItemsPerPage());
        $this->assertEquals(1, $this->restSearchAttributesTransfer->getPagination()->getMaxPage());
        $this->assertEquals(3, $this->restSearchAttributesTransfer->getPagination()->getNumFound());
    }

    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithEmptyAttributesData()
    {
        $this->restSearchAttributesTransfer = $this
            ->searchResourceMapper
            ->mapSearchResponseAttributesTransferToRestResponse($this->mockEmptyRestSearchResponseTransfer())
            ->getAttributes();

        $this->assertEmpty($this->restSearchAttributesTransfer->getProducts());
    }
}
