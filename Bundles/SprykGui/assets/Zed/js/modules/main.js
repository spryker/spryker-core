/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var $collectionHolder;

var $addLink = $('<a href="#" class="add_tag_link">Add more...</a>');
var $addAttribute = $('<p></p>').append($addLink);

/**
 * @param $collectionHolder
 * @param $newLinkLi
 */
function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);

    var $newForm = $('<div></div>').append(newForm);
    $addAttribute.before($newForm);

    $('.type-selector').change(function typeSelected() {
        var $id = $(this).attr('id');
        $id = $id.replace('type', 'variable');

        var proposal = $(this).find('option:selected').getAttribute('data-proposal').val();
        $('#' + $id).val(proposal);
    });
}

function capitalise(string) {
    return string.charAt(1).toLowerCase() + string.slice(1)
}


$(document).ready( function () {
    $collectionHolder = $('div.prototype');
    $collectionHolder.append($addAttribute);

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addLink.on('click', function(e) {
        e.preventDefault();
        addTagForm($collectionHolder, $addAttribute);
    });

    var $typeSelect = $('.type-selector');
    $typeSelect.change(function() {
        var $id = $(this).attr('id');
        var $selectedType = $(this + ' option:selected').html();
        $id = $id.replace('type', 'variable');
        console.log($selectedType);
        console.log($id);
    });



    // var $moduleSelection = $('#spryk_main_form_moduleInformation');
    // $moduleSelection.change(function() {
    //     var $form = $(this).closest('form');
    //     var data = {};
    //     data[$moduleSelection.attr('name')] = $moduleSelection.val();
    //     $.ajax({
    //         url : $form.attr('action'),
    //         type: $form.attr('method'),
    //         data : data,
    //         success: function(html) {
    //             $('#spryk_main_form_sprykDetailsPlaceholder').replaceWith(
    //                 $(html).find('#spryk_main_form_sprykDetails')
    //             );
    //         }
    //     });
    // });
});
