import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { InvokeModule } from '@spryker/utils';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ProductAttributesSelectorComponent } from './product-attributes-selector.component';

const mockName = 'Name';
const mockAttributes = [
    {
        name: 'name1',
        value: 'value1',
        attributes: [
            {
                name: 'name11',
                value: 'value11',
            },
            {
                name: 'name12',
                value: 'value12',
            },
        ],
    },
    {
        name: 'name2',
        value: 'value2',
        attributes: [
            {
                name: 'name21',
                value: 'value21',
            },
        ],
    },
];
const mockSelectedAttributes = [
    {
        name: 'name1',
        value: 'value1',
        attributes: [
            {
                name: 'name11',
                value: 'value11',
            },
        ],
    },
];

describe('ProductAttributesSelectorComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ProductAttributesSelectorComponent, {
        ngModule: {
            imports: [InvokeModule],
            schemas: [NO_ERRORS_SCHEMA],
        },
        projectContent: `
            <span col-attr-name></span>
            <span col-attr-values-name></span>
            <span btn-attr-add-name></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    describe('Slots and components', () => {
        it('should render `col-attr-name` slot to the `.mp-product-attributes-selector__header` element', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const colAttrNameSlot = host.queryCss('.mp-product-attributes-selector__header [col-attr-name]');

            expect(colAttrNameSlot).toBeTruthy();
        });

        it('should render `col-attr-values-name` slot to the `.mp-product-attributes-selector__header` element', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const colAttrValuesNameSlot = host.queryCss(
                '.mp-product-attributes-selector__header [col-attr-values-name]',
            );

            expect(colAttrValuesNameSlot).toBeTruthy();
        });

        it('should render `btn-attr-add-name` slot to the `.mp-product-attributes-selector__button-add` element', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const btnAttrAddNameSlot = host.queryCss('.mp-product-attributes-selector__button-add [btn-attr-add-name]');

            expect(btnAttrAddNameSlot).toBeTruthy();
        });

        it('should render <spy-select> component to the `.mp-product-attributes-selector__content-row-name` element', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: mockSelectedAttributes,
            });
            const selectComponent = host.queryCss('.mp-product-attributes-selector__content-row-name spy-select');

            expect(selectComponent).toBeTruthy();
        });

        it('should render <spy-select> component to the `.mp-product-attributes-selector__content-row-values-name` element', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: mockSelectedAttributes,
            });
            const selectComponent = host.queryCss(
                '.mp-product-attributes-selector__content-row-values-name spy-select',
            );

            expect(selectComponent).toBeTruthy();
        });

        it('should render <spy-button-icon> component to the `.mp-product-attributes-selector__content-row-values-name` element', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [...mockSelectedAttributes, {}],
            });
            const buttonIconComponent = host.queryCss(
                '.mp-product-attributes-selector__content-row-values-name spy-button-icon',
            );

            expect(buttonIconComponent).toBeTruthy();
        });
    });

    describe('Host functionality', () => {
        it('should render hidden <input> element with serialized selected attributes if `@Input(name)` exists', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: mockSelectedAttributes,
                name: mockName,
            });
            const hiddenInputElem = host.queryCss('input[type=hidden]');

            expect(hiddenInputElem).toBeTruthy();
            expect(hiddenInputElem.properties.name).toBe(mockName);
            expect(JSON.parse(hiddenInputElem.properties.value)).toEqual(mockSelectedAttributes);
        });

        it('should add a new attribute row by `Add` button click', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const buttonComponent = host.queryCss('.mp-product-attributes-selector__button-add spy-button');
            const selectComponents = host.fixture.debugElement.queryAll(
                By.css('.mp-product-attributes-selector__content-row-name spy-select'),
            );

            expect(selectComponents.length).toBe(1);

            buttonComponent.triggerEventHandler('click', null);
            host.detectChanges();

            const updatedSelectComponents = host.fixture.debugElement.queryAll(
                By.css('.mp-product-attributes-selector__content-row-name spy-select'),
            );

            expect(updatedSelectComponents.length).toBe(2);
        });

        it('should remove attribute row by `Delete` button click', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [...mockSelectedAttributes, {}],
                name: mockName,
            });
            const buttonIconComponents = host.fixture.debugElement.queryAll(
                By.css('.mp-product-attributes-selector__content-row-values-name spy-button-icon'),
            );
            const selectComponents = host.fixture.debugElement.queryAll(
                By.css('.mp-product-attributes-selector__content-row-name spy-select'),
            );

            expect(selectComponents.length).toBe(2);

            buttonIconComponents[0].triggerEventHandler('click', 0);
            host.detectChanges();

            const updatedSelectComponents = host.fixture.debugElement.queryAll(
                By.css('.mp-product-attributes-selector__content-row-name spy-select'),
            );

            expect(updatedSelectComponents.length).toBe(1);
        });

        it('should update selected attributes by `Super attribute` select change', async () => {
            const expectedValue = 'value1';
            const expectedSelectedSuperAttribute = [
                {
                    name: 'name1',
                    value: expectedValue,
                    attributes: [],
                    isDisabled: false,
                },
            ];
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
                name: mockName,
            });
            const selectComponent = host.queryCss('.mp-product-attributes-selector__content-row-name spy-select');
            const hiddenInputElem = host.queryCss('input[type=hidden]');

            expect(JSON.parse(hiddenInputElem.properties.value)).toEqual([{}]);

            host.hostComponent.selectedAttributesChange = jest.fn();
            selectComponent.triggerEventHandler('valueChange', expectedValue);
            host.detectChanges();

            expect(JSON.parse(hiddenInputElem.properties.value)).toEqual(expectedSelectedSuperAttribute);
            expect(host.hostComponent.selectedAttributesChange).toHaveBeenCalledWith(expectedSelectedSuperAttribute);
        });

        it('should update selected attributes by `Values` select change', async () => {
            const expectedValue = 'value1';
            const expectedValues = {
                name: 'name11',
                value: 'value11',
            };
            const expectedSelectedSuperAttribute = [
                {
                    name: 'name1',
                    value: expectedValue,
                    attributes: [expectedValues],
                    isDisabled: false,
                },
            ];
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
                name: mockName,
            });
            const selectComponent = host.queryCss('.mp-product-attributes-selector__content-row-name spy-select');
            const hiddenInputElem = host.queryCss('input[type=hidden]');

            host.hostComponent.selectedAttributesChange = jest.fn();
            selectComponent.triggerEventHandler('valueChange', expectedValue);
            host.detectChanges();

            const valuesSelectComponent = host.queryCss(
                '.mp-product-attributes-selector__content-row-values-name spy-select',
            );

            valuesSelectComponent.triggerEventHandler('valueChange', [expectedValues.value]);
            host.detectChanges();

            expect(JSON.parse(hiddenInputElem.properties.value)).toEqual(expectedSelectedSuperAttribute);
            expect(host.hostComponent.selectedAttributesChange).toHaveBeenCalledWith(expectedSelectedSuperAttribute);
        });
    });
});
