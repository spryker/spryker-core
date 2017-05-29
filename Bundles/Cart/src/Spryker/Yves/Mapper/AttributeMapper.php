<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Cart\Mapper;

class AttributeMapper
{

    /**
     * @var \Spryker\Client\ProductOption\ProductOptionClientInterface
     */
    protected $productOptionsClient;

    /**
     * @var \Spryker\Client\Availability\AvailabilityClientInterface
     */
    protected $productAvailabilityClient;

    /**
     * @param \Spryker\Client\ProductOption\ProductOptionClientInterface $productOptionsClient
     * @param \Spryker\Client\Availability\AvailabilityClientInterface $productAvailabilityClient
     */
    public function __construct($productOptionsClient, $productAvailabilityClient)
    {
        $this->productOptionsClient = $productOptionsClient;
        $this->productAvailabilityClient = $productAvailabilityClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     */
    public function buildMap($items)
    {
        foreach ($items as $item) {
            $item->getSku();
            $item->getIdProductAbstract();
        }
    }

    protected function getAttributesFromSku($productAbstractId)
    {
        $this->productOptionsClient->getProductOptions($productAbstractId, 'en_US');

    }

    /*
     {
  "productConcreteIds": [208,209,210],
  "superAttributes": {
    "processor_frequency": [
      "2.6 GHz",
      "2.2 GHz",
      "2.1 GHz"
    ]
  },
  "attributeVariants": {
    "processor_frequency:2.6 GHz": {
      "id_product_concrete": 208
    },
    "processor_frequency:2.2 GHz": {
      "id_product_concrete": 209
    },
    "processor_frequency:2.1 GHz": {
      "id_product_concrete": 210
    }
  }
}

    kv:de.en_us.resource.attribute_map.159

    {
  "productConcreteIds": [154,155,156],
  "superAttributes": {
    "processor_frequency": [
      "3 GHz",
      "2.8 GHz",
      "3.2 GHz"
    ]
  },
  "attributeVariants": {
    "processor_frequency:3 GHz": {
      "id_product_concrete": 154
    },
    "processor_frequency:2.8 GHz": {
      "id_product_concrete": 155
    },
    "processor_frequency:3.2 GHz": {
      "id_product_concrete": 156
    }
  }
}
     */




}