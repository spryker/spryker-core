<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

interface SearchFacadeInterface
{

    /**
     * Specification:
     * - Loads index definition json files from the folders
     * - Installs Elasticsearch indexes and mapping types based on the loaded index definitions if they not exists already
     * - For each configured store a separated index will be created
     * - The name of the index is automatically prefixed with the store name + underscore
     * - Generates IndexMap class for each mapping type
     * - The generated IndexMaps are not store specific and has the class name of the mapping types suffixed with "IndexMap"
     * - The generated files will be removed and re-created always when install runs
     *
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger);

    /**
     * Specification:
     * - Returns the total number of documents in the current index
     *
     * @api
     *
     * @return int
     */
    public function getTotalCount();

    /**
     * Specification:
     * - Returns the metadata information from the current index
     * - Returns empty array if the index is not installed
     *
     * @api
     *
     * @return array
     */
    public function getMetaData();

    /**
     * Specification:
     * - Removes the current index
     *
     * @api
     *
     * @return \Elastica\Response
     */
    public function delete();

    /**
     * Specification:
     * - Returns a document from the current index with the given key in the given mapping type
     *
     * @api
     *
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type);

    /**
     * Specification:
     * - Runs a simple full text search for the given search string
     * - Returns the raw result set ordered by relevance
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet
     */
    public function searchKeys($searchString, $limit = null, $offset = null);

    /**
     * Specification:
     * - Transforms a raw data array into an Elasticsearch "page" mapping type document
     * - The transformation is based on the given page map what configures which data goes into which field
     *
     * @api
     *
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function transformPageMapToDocument(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer);

    /**
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function generatePageIndexMap(MessengerInterface $messenger);

}
