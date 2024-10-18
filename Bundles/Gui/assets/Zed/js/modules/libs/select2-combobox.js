'use strict';

function select2combobox(selector) {
    var select2Selector = selector || '.spryker-form-select2combobox';

    $(select2Selector).each(function (index, element) {
        var select2InitOptions = {};
        var $selectElement = $(element);
        var autocompleteUrl = $selectElement.data('autocomplete-url');
        var preloadUrl = $selectElement.data('dependent-preload-url');
        var disablePlaceholder = $selectElement.data('disable-placeholder');
        var clearInitial = $selectElement.data('clear-initial');
        var minimumInputLengthValue = Number($selectElement.data('minimum-input-length'));
        var parentFieldSelectors = $selectElement.data('depends-on-field')?.split(',');
        var $parentFields = parentFieldSelectors?.map((parentFieldSelector) => $(parentFieldSelector));
        var $placeholderOption = disablePlaceholder ? $selectElement.find('option:first') : null;
        var cleanSelect = () => $selectElement.empty();
        var insertPlaceholderOption = () => ($placeholderOption ? $selectElement.append($placeholderOption) : null);

        if (disablePlaceholder) {
            $placeholderOption?.attr('disabled', 'disabled');
        }

        if (clearInitial) {
            cleanSelect();
            insertPlaceholderOption();
        }

        if (autocompleteUrl) {
            select2InitOptions = {
                ajax: {
                    url: autocompleteUrl,
                    dataType: 'json',
                    delay: 500,
                    cache: true,
                    data: function (params) {
                        params.page = params.page || 1;
                        for (const $parentField of $parentFields) {
                            if ($parentField) {
                                var autocompleteKey = $selectElement.data('dependent-autocomplete-key');
                                params[autocompleteKey] = $parentField.val();
                            }
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

        if ($parentFields) {
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
                for (const $parentField of $parentFields) {
                    $parentField.on('change', function () {
                        $selectElement.val(null).trigger('change');
                    });
                }
            }

            if (preloadUrl) {
                for (const $parentField of $parentFields) {
                    $parentField.on('change', function () {
                        const query = new URLSearchParams();

                        $parentFields.forEach(($parentField) =>
                            query.append($parentField.data('dependent-name'), $parentField.val()),
                        );

                        $selectElement.prop('disabled', true);

                        $.ajax({
                            url: `${preloadUrl}${query}`,
                        }).done(function (data) {
                            $placeholderOption?.removeAttr('disabled');
                            cleanSelect();
                            insertPlaceholderOption();

                            for (const [key, value] of Object.entries(data)) {
                                $selectElement.append(new Option(key, value, false, false));
                            }

                            $selectElement.prop('disabled', false);
                            $placeholderOption?.attr('disabled', 'disabled');
                        });
                    });
                }
            }
        }

        $selectElement.select2(select2InitOptions);
    });
}

module.exports = select2combobox;
