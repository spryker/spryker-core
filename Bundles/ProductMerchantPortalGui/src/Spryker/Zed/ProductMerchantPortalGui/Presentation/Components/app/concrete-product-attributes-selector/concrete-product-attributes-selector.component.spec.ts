import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { InvokeModule } from '@spryker/utils';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ConcreteProductAttributesSelectorComponent } from './concrete-product-attributes-selector.component';

const mockName = 'Name';
const mockPlaceholder = 'Placeholder';
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
const mockAttributeErrors = [
    {
        error: 'attribute error',
    },
];

describe('ConcreteProductAttributesSelectorComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ConcreteProductAttributesSelectorComponent, {
        ngModule: {
            imports: [InvokeModule],
            schemas: [NO_ERRORS_SCHEMA],
        },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it(`should render <spy-form-item> component with '${mockAttributes[0].name}' text`, async () => {
        const host = await createComponentWrapper(createComponent, {
            attributes: mockAttributes,
            selectedAttributes: [],
        });
        const formItemComponent = host.queryCss('spy-form-item');

        expect(formItemComponent.nativeElement.textContent.trim()).toBe(mockAttributes[0].name);
    });

    it(`should render ${mockAttributes.length} <spy-form-item> components with <spy-select> component inside`, async () => {
        const host = await createComponentWrapper(createComponent, {
            attributes: mockAttributes,
            selectedAttributes: [],
        });
        const selectComponents = host.fixture.debugElement.queryAll(By.css('spy-form-item spy-select'));

        expect(selectComponents.length).toBe(mockAttributes.length);
    });

    describe('<spy-select>', () => {
        it(`should have 'multiple' and 'control' attributes`, async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const selectComponent = host.queryCss('spy-form-item spy-select');

            expect('multiple' in selectComponent.attributes).toBeTruthy();
            expect('control' in selectComponent.attributes).toBeTruthy();
        });

        it(`should have 'options' attribute with '${mockAttributes[0].attributes.length}' items`, async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const selectComponent = host.queryCss('spy-form-item spy-select');

            expect(selectComponent.properties.options.length).toBe(mockAttributes[0].attributes.length);
        });

        it(`should have 'value' attribute with '${mockSelectedAttributes[0].attributes[0].value}'`, async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: mockSelectedAttributes,
            });
            const selectComponent = host.queryCss('spy-form-item spy-select');

            expect(selectComponent.properties.value[0]).toBe(mockSelectedAttributes[0].attributes[0].value);
        });

        it(`should have 'placeholder' attribute with '${mockPlaceholder}'`, async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
                placeholder: mockPlaceholder,
            });
            const selectComponent = host.queryCss('spy-form-item spy-select');

            expect(selectComponent.properties.placeholder).toBe(mockPlaceholder);
        });
    });

    it(`should render hidden <input> element with serialized selected attributes if '@Input(name) exists'`, async () => {
        const host = await createComponentWrapper(createComponent, {
            attributes: mockAttributes,
            selectedAttributes: mockSelectedAttributes,
            name: mockName,
        });
        const hiddenInputElem = host.queryCss('input[type=hidden]');

        expect(hiddenInputElem).toBeTruthy();
        expect(hiddenInputElem.properties.name).toBe(mockName);
        expect(JSON.parse(hiddenInputElem.properties.value)[0]).toStrictEqual(mockSelectedAttributes[0]);
    });

    it('should bound `@Input(errors)` to the `error` input of <spy-form-item> component', async () => {
        const host = await createComponentWrapper(createComponent, {
            attributes: mockAttributes,
            selectedAttributes: [],
            errors: mockAttributeErrors,
        });
        const formItemComponent = host.queryCss('spy-form-item');

        expect(formItemComponent.properties.error).toBe(mockAttributeErrors[0].error);
    });

    it('should remove `error` property of <spy-form-item> component after update select changing', async () => {
        const host = await createComponentWrapper(createComponent, {
            attributes: mockAttributes,
            selectedAttributes: [],
            errors: mockAttributeErrors,
        });
        const selectComponent = host.queryCss('spy-form-item spy-select');

        selectComponent.triggerEventHandler('valueChange', [mockSelectedAttributes[0].attributes[0].value]);
        host.detectChanges();

        const formItemComponent = host.queryCss('spy-form-item');

        expect(formItemComponent.properties.error).toBeFalsy();
    });

    it(`should emit 'selectedAttributesChange' output by select change`, async () => {
        const expectedSelectedAttributes = [
            mockSelectedAttributes[0],
            {
                name: 'name2',
                value: 'value2',
                attributes: [],
            },
        ];
        const host = await createComponentWrapper(createComponent, {
            attributes: mockAttributes,
            selectedAttributes: [],
            name: mockName,
        });
        const selectComponent = host.queryCss('spy-form-item spy-select');

        host.hostComponent.selectedAttributesChange = jest.fn();
        selectComponent.triggerEventHandler('valueChange', [mockSelectedAttributes[0].attributes[0].value]);
        host.detectChanges();

        expect(host.hostComponent.selectedAttributesChange).toHaveBeenCalledWith(expectedSelectedAttributes);
    });
});
