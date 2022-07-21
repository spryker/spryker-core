'use strict';

function select2combobox(selector) {
    var select2Selector = selector || '.spryker-form-select2combobox';

    $(select2Selector).each(function (index, element) {
        var select2InitOptions = {};
        var $selectElement = $(element);
        var autocompleteUrl = $selectElement.data('autocomplete-url');
        var minimumInputLengthValue = Number($selectElement.data('minimum-input-length'));
        var parentFieldSelector = $selectElement.data('depends-on-field');
        var $parentField = $(parentFieldSelector);

        if (autocompleteUrl) {
            select2InitOptions = {
                ajax: {
                    url: autocompleteUrl,
                    dataType: 'json',
                    delay: 500,
                    cache: true,
                    data: function (params) {
                        params.page = params.page || 1;
                        if ($parentField) {
                            var autocompleteKey = $selectElement.data('dependent-autocomplete-key');
                            params[autocompleteKey] = $parentField.val();
                        }
                        return params;
                    },
                },
                minimumInputLength: minimumInputLengthValue || 3,
            };

            $selectElement.on('select2:unselecting', function (e) {
                var idSelected = String(e.params.args.data.id);
                var selectedValues = $selectElement.val();

                $selectElement
                    .val(
                        selectedValues.filter(function (value) {
                            return value !== idSelected;
                        }),
                    )
                    .trigger('change');
            });
        }

        if ($parentField) {
            var disableSelectElementWhenParentEmpty = $selectElement[0].hasAttribute(
                'data-dependent-disable-when-empty',
            );
            var resetSelectElementOnParentChange = $selectElement[0].hasAttribute('data-dependent-reset-on-change');

            if (disableSelectElementWhenParentEmpty) {
                if (!$parentField.val()) {
                    $selectElement.prop('disabled', true);
                }

                $parentField.on('change', function () {
                    $selectElement.prop('disabled', !$parentField.val());
                });
            }

            if (resetSelectElementOnParentChange) {
                $parentField.on('change', function () {
                    $selectElement.val(null).trigger('change');
                });
            }
        }

        $selectElement.select2(select2InitOptions);
    });
}

module.exports = select2combobox;
