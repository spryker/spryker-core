
var xhr = null;
var keyList = null;
var keyContainer = null;

function postForm( $form, id, successCallback ){

    var values = {};
    $.each( $form.serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });
    $.ajax({
        type        : $form.attr( 'method' ),
        url         : '?id-page=' + $('#idPage').val() +'&id-form=' + id,
        data        : values,
        success     : function(data) {
            successCallback(data);
        }
    });
}

var ajaxifySubmmit = function(formId) {
    $('.form_class_' + formId).submit( function( e ){
        e.preventDefault();

        $('.waiting_' + formId).text('Waiting ...');
        $('.success_' + formId).text('');
        $('.error_' + formId).text('');

        postForm( $(this),  (formId - 1),
            function( response ){
                $('.waiting_' + formId).text('');
            if(response.success != 'false'){
                $('.success_' + formId).text('Successfully added.');
            }else{
                $('.error_' + formId).text(response.errorMessages);
            }
        });

        return false;
    });
}

function showAutoComplete(formId, type) {
    var listElement = '<div id="foundKeyListContainer" class="key-container"><select id="foundKeyList" size="10" class="key-list"></select></div>'
    $('.keyListCanvas').empty();
    $('.keyListCanvas').append(listElement);

    keyList = $('#foundKeyList');
    keyContainer = $('#foundKeyListContainer');

    var form = $('.form_class_' + formId);

    var keyInput = form.find('#form_glossary_key');
    var ketTranslation = form.find('#form_translation');
    var ajaxUrl = type == 1 ? 'search/?key=' : 'search/?value=';

    keyList.find('option').remove();
    $('.loading-' + formId).show();

    xhr = $.ajax({
        type        : 'GET',
        url         : ajaxUrl + keyInput.val(),
        success     : function(data) {
            $('.loading-' + formId).hide();

            $.each(data, function (i, item) {
                keyList.append($('<option>', {
                    value: i,
                    text : item.key
                }));

                keyContainer.css({ top: keyInput.offset().top - 137 });
                keyContainer.css({ left: keyInput.offset().left - 230 });
                keyContainer.css({ width: keyInput.width() + 25 });
                keyContainer.show();
            });

            keyList.css({ height :  data.length * 17 });
            keyList.on('change', function() {
                ketTranslation.text(data[this.value].value);
                keyInput.val(data[this.value].key);
            });

            keyList.on('keydown', function(e) {
                var key = e.keyCode;
                if (key == 13 || key == 9) {
                    keyList.blur();
                    return false;
                }
            });

            keyList.on('blur', function() {
                keyInput.val(data[this.value].key);
                keyContainer.hide();
                keyInput.focus();
                return false;
            });
        },
    });
}

var addKeySearchEvent = function(formId) {
    var form = $('.form_class_' + formId);
    var keyInput = form.find('#form_glossary_key');
    var keyType = form.find('#form_search_option');

    keyInput.on('input', function() {
        if($(this).val().length > 3){
            delay(function(){
                if(xhr && xhr.readystate != 4){
                    xhr.abort();
                }
                if (keyType.val() != 0) {
                    showAutoComplete(formId, keyType.val());
                }
            }, 500 );
        }
    });

    keyInput.on('keyup', function(e) {
        var key = e.keyCode;

        if (key == 40) {
            keyList.first().focus();
            keyList.val(0).change();
        }
    });
}

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

$(document).ready(function(){
    $('.cms_form').each(function(index, item){
        var formId = $(item).attr('data-index');
        ajaxifySubmmit(formId);
        addKeySearchEvent(formId);
    });
});

$(document).on('click', function(e) {
    if (keyContainer !== null && !$(e.target).is('option')) {
        keyContainer.hide();
    }
});
