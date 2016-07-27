<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Shared\Library\Collection\CollectionInterface;

interface AttributeProcessorInterface
{

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAbstractAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $abstractAttributes
     *
     * @return $this
     */
    public function setAbstractAttributes(CollectionInterface $abstractAttributes);

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getConcreteAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $concreteAttributes
     *
     * @return $this
     */
    public function setConcreteAttributes(CollectionInterface $concreteAttributes);

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getConcreteLocalizedAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $concreteLocalizedAttributes
     *
     * @return $this
     */
    public function setConcreteLocalizedAttributes(CollectionInterface $concreteLocalizedAttributes);

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    public function getAbstractLocalizedAttributes();

    /**
     * @param \Spryker\Shared\Library\Collection\CollectionInterface $abstractLocalizedAttributes
     *
     * @return $this
     */
    public function setAbstractLocalizedAttributes(CollectionInterface $abstractLocalizedAttributes);

    /**
     * @return array
     */
    public function mergeAttributes();

    /**
     * @return array
     */
    public function mergeAbstractAttributes();

    /**
     * @return array
     */
    public function mergeConcreteAttributes();

    /**
     * @param array $values
     *
     * @return array
     */
    public function generateAttributeValues(array $values);

}
