/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

$(document).ready(function() {

    /**
     * Register global event listeners
     */
    $('body')
        .on('click', '.add-another-image-set', addAnotherImageSet)
        .on('click', '.add-another-image-collection', addAnotherImageCollection)
        .on('click', '.remove-image-set', deleteImageSet)
        .on('click', '.remove-image-collection', deleteImageCollection);

    /**
     * @param event
     */
    function addAnotherImageCollection(event) {
        event.preventDefault();

        var prototypeTemplate = $(event.target).closest('[data-image-collection-prototype]');

        var imageSet = $(event.target).closest('.image-set');
        var imageSetIndex = imageSet.data('imageSetIndex');
        var imageCollectionIndex = imageSet.find('.image-collection').length;

        var newOptionFormHTML = prototypeTemplate
            .data('imageCollectionPrototype')
            .replace(/__image_set_name__/g, imageSetIndex)
            .replace(/__name__/g, imageCollectionIndex);

        newOptionFormHTML = setSortOrderFieldValue(newOptionFormHTML);

        imageSet.find('.image-collection-container').append($(newOptionFormHTML));
    }

    /**
     * @param event
     */
    function addAnotherImageSet(event) {
        event.preventDefault();

        var prototypeTemplate = $(event.target).closest('[data-image-set-prototype][data-image-collection-prototype]');
        var imageSetIndex = prototypeTemplate.data('currentImageSetIndex') + 1;
        prototypeTemplate.data('currentImageSetIndex', imageSetIndex);

        var imageSetPrototype = prototypeTemplate
            .data('imageSetPrototype')
            .replace(/__image_set_name__/g, imageSetIndex);

        var imageSet = $(imageSetPrototype);
        imageSet.data('imageSetIndex', imageSetIndex);
        $(event.target).before(imageSet);

        var imageCollectionPrototype = prototypeTemplate
            .data('imageCollectionPrototype')
            .replace(/__image_set_name__/g, imageSetIndex)
            .replace(/__name__/g, 0);

        imageCollectionPrototype = setSortOrderFieldValue(imageCollectionPrototype);

        imageSet.find('.image-collection-container').append($(imageCollectionPrototype));
    }

    /**
     * @param imageCollectionPrototype
     */
    function setSortOrderFieldValue(imageCollectionPrototype) {
        imageCollectionPrototype = $($.parseHTML(imageCollectionPrototype));

        var sortOrderField = imageCollectionPrototype.find('[data-sort-order]');
        sortOrderField.val(sortOrderField.data('sort-order'));

        return imageCollectionPrototype;
    }

    /**
     * @param event
     */
    function deleteImageSet(event) {
        event.preventDefault();

        $(this).closest('.image-set').remove();
    }

    /**
     * @param event
     */
    function deleteImageCollection(event) {
        event.preventDefault();

        $(this).closest('.image-collection').remove();
    }

    /**
     * Init image set index
     */
    $('.image-set').each(function(i, imageSet) {
        var imageSetIndex = $(imageSet).closest('[data-image-set-prototype]').find('.image-set').index(imageSet);

        $(imageSet).data('imageSetIndex', imageSetIndex);
    });
    $('.image-set-container').each(function(i, imageSetContainer) {
        var currentImageSetIndex = $(imageSetContainer).find('.image-set').length - 1;

        $(imageSetContainer).data('currentImageSetIndex', currentImageSetIndex);
    });

});
