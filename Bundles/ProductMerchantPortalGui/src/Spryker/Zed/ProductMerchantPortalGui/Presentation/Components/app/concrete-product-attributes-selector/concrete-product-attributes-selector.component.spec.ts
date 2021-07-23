import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { InvokeModule } from '@spryker/utils';
import { ConcreteProductAttributesSelectorComponent } from './concrete-product-attributes-selector.component';
import { ProductAttribute, ProductAttributeError } from '../../services/types';

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

@Component({
    selector: 'spy-test',
    template: `
        <mp-concrete-product-attributes-selector
            [attributes]="attributes"
            [selectedAttributes]="selectedAttributes"
            [name]="name"
            [placeholder]="placeholder"
            [errors]="errors"
            (selectedAttributesChange)="changeEvent($event)"
        >
        </mp-concrete-product-attributes-selector>
    `,
})
class TestComponent {
    attributes: ProductAttribute[] = [];
    selectedAttributes: ProductAttribute[] = [];
    name?: string;
    placeholder?: string;
    errors?: ProductAttributeError[];
    changeEvent = jest.fn();
}

describe('ConcreteProductAttributesSelectorComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [InvokeModule],
            declarations: [ConcreteProductAttributesSelectorComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
        component.attributes = mockAttributes;
        fixture.detectChanges();
    });

    it(`should render <spy-form-item> with text '${mockAttributes[0].name}'`, () => {
        const formItem = fixture.debugElement.query(By.css('spy-form-item'));

        expect(formItem.nativeElement.textContent.trim()).toBe(mockAttributes[0].name);
    });

    it(`should render ${mockAttributes.length} <spy-form-item> with <spy-select> inside`, () => {
        const selects = fixture.debugElement.queryAll(By.css('spy-form-item spy-select'));

        expect(selects.length).toBe(mockAttributes.length);
    });

    describe(`<spy-select>`, () => {
        it(`should have 'multiple' and 'control' attributes`, () => {
            const select = fixture.debugElement.query(By.css('spy-form-item spy-select'));

            expect('multiple' in select.attributes).toBeTruthy();
            expect('control' in select.attributes).toBeTruthy();
        });

        it(`'options' attribute with '${mockAttributes[0].attributes.length}' items`, () => {
            const select = fixture.debugElement.query(By.css('spy-form-item spy-select'));

            expect(select.properties.options.length).toBe(mockAttributes[0].attributes.length);
        });

        it(`'value' attribute with '${mockSelectedAttributes[0].attributes[0].value}'`, () => {
            component.selectedAttributes = mockSelectedAttributes;
            fixture.detectChanges();

            const select = fixture.debugElement.query(By.css('spy-form-item spy-select'));

            expect(select.properties.value[0]).toBe(mockSelectedAttributes[0].attributes[0].value);
        });

        it(`'placeholder' attribute with '${mockPlaceholder}'`, () => {
            component.placeholder = mockPlaceholder;
            fixture.detectChanges();

            const select = fixture.debugElement.query(By.css('spy-form-item spy-select'));

            expect(select.properties.placeholder).toBe(mockPlaceholder);
        });
    });

    it(`should render hidden <input> element with serialized selected attributes if '@Input(name) exists'`, () => {
        component.selectedAttributes = mockSelectedAttributes;
        component.name = mockName;
        fixture.detectChanges();

        const hiddenInput = fixture.debugElement.query(By.css('input[type=hidden]'));

        expect(hiddenInput).toBeTruthy();
        expect(hiddenInput.properties.name).toBe(mockName);
        expect(JSON.parse(hiddenInput.properties.value)).toStrictEqual(mockSelectedAttributes);
    });

    it(`should bound '@Input(errors)' to the input 'error' of <spy-form-item> component`, () => {
        component.errors = mockAttributeErrors;
        fixture.detectChanges();

        const formItem = fixture.debugElement.query(By.css('spy-form-item'));

        expect(formItem.properties.error).toBe(mockAttributeErrors[0].error);
    });

    it(`should remove 'error' of <spy-form-item> component after update select changing`, () => {
        component.errors = mockAttributeErrors;
        fixture.detectChanges();

        const select = fixture.debugElement.query(By.css('spy-form-item spy-select'));

        select.triggerEventHandler('valueChange', [mockSelectedAttributes[0].attributes[0].value]);
        fixture.detectChanges();

        const formItem = fixture.debugElement.query(By.css('spy-form-item'));

        expect(formItem.properties.error).toBeFalsy();
    });

    it(`should emit 'selectedAttributesChange' output by select change`, () => {
        const expectedSelectedAttributes = [
            mockSelectedAttributes[0],
            {
                name: 'name2',
                value: 'value2',
                attributes: [],
            },
        ];

        component.name = mockName;
        fixture.detectChanges();

        const select = fixture.debugElement.query(By.css('spy-form-item spy-select'));

        select.triggerEventHandler('valueChange', [mockSelectedAttributes[0].attributes[0].value]);
        fixture.detectChanges();

        expect(component.changeEvent).toHaveBeenCalledWith(expectedSelectedAttributes);
    });
});
