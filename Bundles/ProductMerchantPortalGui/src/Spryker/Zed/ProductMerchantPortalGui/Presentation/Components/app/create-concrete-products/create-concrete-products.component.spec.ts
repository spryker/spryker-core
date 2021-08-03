import { Component, EventEmitter, Input, NO_ERRORS_SCHEMA, Output } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { CreateConcreteProductsComponent } from './create-concrete-products.component';
import {
    ProductAttribute,
    ProductAttributeError,
    ConcreteProductPreview,
    ConcreteProductPreviewErrors,
} from '../../services/types';

const mockProductsName = 'Products Name';
const mockAttributesName = 'Attributes Name';
const mockAttributesPlaceholder = 'Attributes Placeholder';
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
const mockAttributeErrors = [
    {
        error: 'attribute error',
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
const mockExistingProducts = [
    {
        name: '',
        sku: '',
        superAttributes: [
            {
                name: 'name1',
                value: 'value1',
                attribute: {
                    name: 'name12',
                    value: 'value12',
                },
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                },
            },
        ],
    },
];
const mockGeneratedProducts = [
    {
        name: '',
        sku: '',
        superAttributes: [
            {
                name: 'name1',
                value: 'value1',
                attribute: {
                    name: 'name11',
                    value: 'value11',
                },
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                },
            },
        ],
    },
    {
        name: '',
        sku: '',
        superAttributes: [
            {
                name: 'name1',
                value: 'value1',
                attribute: {
                    name: 'name12',
                    value: 'value12',
                },
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                },
            },
        ],
    },
];
const mockGeneratedProductErrors = [
    {
        fields: {
            name: '',
            sku: '123',
        },
        errors: {
            name: 'This value should not be blank.',
            sku: 'SKU Prefix already exists',
        },
    },
    {},
];

@Component({
    selector: 'spy-test',
    template: `
        <mp-create-concrete-products>
            <span preview-text></span>
            <span preview-total-text></span>
            <span preview-auto-sku-text></span>
            <span preview-auto-name-text></span>
            <span preview-col-attr-name></span>
            <span preview-col-sku-name></span>
            <span preview-col-name-name></span>
            <span preview-no-data-text></span>
        </mp-create-concrete-products>
    `,
})
class TestComponent {}

@Component({
    selector: `mp-concrete-product-attributes-selector`,
    template: '',
})
class MockConcreteProductAttributesSelectorComponent {
    @Input() attributes: ProductAttribute[];
    @Input() selectedAttributes: ProductAttribute[];
    @Input() name: string;
    @Input() placeholder: string;
    @Input() errors: ProductAttributeError[];
    @Output() selectedAttributesChange = new EventEmitter<ProductAttribute[]>();
}

@Component({
    selector: `mp-concrete-products-preview`,
    template: '',
})
class MockConcreteProductsPreviewComponent {
    @Input() attributes: ProductAttribute[];
    @Input() generatedProducts: ConcreteProductPreview[];
    @Input() existingProducts?: ConcreteProductPreview[];
    @Input() errors: ConcreteProductPreviewErrors[];
    @Input() name: string;
}

describe('CreateConcreteProductsComponent', () => {
    describe('Slots and Components', () => {
        let component: TestComponent;
        let fixture: ComponentFixture<TestComponent>;

        beforeEach(async(() => {
            TestBed.configureTestingModule({
                declarations: [CreateConcreteProductsComponent, TestComponent],
                schemas: [NO_ERRORS_SCHEMA],
            }).compileComponents();
        }));

        beforeEach(() => {
            fixture = TestBed.createComponent(TestComponent);
            component = fixture.componentInstance;
        });

        it(`should render <mp-concrete-product-attributes-selector> component`, () => {
            const concreteProductAttributesSelector = fixture.debugElement.query(
                By.css('mp-concrete-product-attributes-selector'),
            );

            expect(concreteProductAttributesSelector).toBeTruthy();
        });

        it(`should render 'preview-text' slot into the '.mp-create-concrete-products__preview-title' element`, () => {
            const previewText = fixture.debugElement.query(
                By.css('.mp-create-concrete-products__preview-title [preview-text]'),
            );

            expect(previewText).toBeTruthy();
        });

        it('should render <mp-concrete-products-preview> component', () => {
            const concreteProductsPreview = fixture.debugElement.query(By.css('mp-concrete-products-preview'));

            expect(concreteProductsPreview).toBeTruthy();
        });

        describe('<mp-concrete-products-preview> component', () => {
            it(`should render 'preview-total-text' slot`, () => {
                const previewTotalText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-total-text]'),
                );

                expect(previewTotalText).toBeTruthy();
            });

            it(`should render 'preview-auto-sku-text' slot`, () => {
                const previewAutoSkuText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-sku-text]'),
                );

                expect(previewAutoSkuText).toBeTruthy();
            });

            it(`should render 'preview-auto-name-text' slot`, () => {
                const previewAutoNameText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-name-text]'),
                );

                expect(previewAutoNameText).toBeTruthy();
            });

            it(`should render 'preview-col-attr-name' slot`, () => {
                const previewColAttrName = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-attr-name]'),
                );

                expect(previewColAttrName).toBeTruthy();
            });

            it(`should render 'preview-col-sku-name' slot`, () => {
                const previewColSkuName = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-sku-name]'),
                );

                expect(previewColSkuName).toBeTruthy();
            });

            it(`should render 'preview-col-name-name' slot`, () => {
                const previewColNameName = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-name-name]'),
                );

                expect(previewColNameName).toBeTruthy();
            });

            it(`should render 'preview-no-data-text' slot`, () => {
                const previewNoDataText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-no-data-text]'),
                );

                expect(previewNoDataText).toBeTruthy();
            });
        });
    });

    describe('@Inputs', () => {
        let component: CreateConcreteProductsComponent;
        let fixture: ComponentFixture<CreateConcreteProductsComponent>;

        beforeEach(async(() => {
            TestBed.configureTestingModule({
                declarations: [
                    CreateConcreteProductsComponent,
                    MockConcreteProductAttributesSelectorComponent,
                    MockConcreteProductsPreviewComponent,
                ],
                schemas: [NO_ERRORS_SCHEMA],
            }).compileComponents();
        }));

        beforeEach(() => {
            fixture = TestBed.createComponent(CreateConcreteProductsComponent);
            component = fixture.componentInstance;
        });

        it(`should bound '@Input(attributes)' to <mp-concrete-product-attributes-selector> component 'attributes' input`, () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const concreteProductAttributesSelector = fixture.debugElement.query(
                By.css('mp-concrete-product-attributes-selector'),
            );

            expect(concreteProductAttributesSelector.componentInstance.attributes).toBe(mockAttributes);
        });

        it(`should bound '@Input(attributeErrors)' to <mp-concrete-product-attributes-selector> component 'errors' input`, () => {
            component.attributeErrors = mockAttributeErrors;
            fixture.detectChanges();

            const concreteProductAttributesSelector = fixture.debugElement.query(
                By.css('mp-concrete-product-attributes-selector'),
            );

            expect(concreteProductAttributesSelector.componentInstance.errors).toBe(mockAttributeErrors);
        });

        it(`should bound '@Input(selectedAttributes)' to <mp-concrete-product-attributes-selector> component 'selectedAttributes' input and to <mp-concrete-products-preview> component 'attributes' input`, () => {
            component.selectedAttributes = mockSelectedAttributes;
            fixture.detectChanges();

            const concreteProductAttributesSelector = fixture.debugElement.query(
                By.css('mp-concrete-product-attributes-selector'),
            );
            const concreteProductsPreview = fixture.debugElement.query(By.css('mp-concrete-products-preview'));

            expect(concreteProductAttributesSelector.componentInstance.selectedAttributes).toBe(mockSelectedAttributes);
            expect(concreteProductsPreview.componentInstance.attributes).toBe(mockSelectedAttributes);
        });

        it(`should bound '@Input(existingProducts)' to <mp-concrete-products-preview> component 'existingProducts' input`, () => {
            component.existingProducts = mockExistingProducts;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(By.css('mp-concrete-products-preview'));

            expect(concreteProductsPreview.componentInstance.existingProducts).toBe(mockExistingProducts);
        });

        it(`should bound '@Input(generatedProducts)' to <mp-concrete-products-preview> component 'generatedProducts' input`, () => {
            component.generatedProducts = mockGeneratedProducts;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(By.css('mp-concrete-products-preview'));

            expect(concreteProductsPreview.componentInstance.generatedProducts).toBe(mockGeneratedProducts);
        });

        it(`should bound '@Input(generatedProductErrors)' to <mp-concrete-products-preview> component 'errors' input`, () => {
            component.generatedProductErrors = mockGeneratedProductErrors;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(By.css('mp-concrete-products-preview'));

            expect(concreteProductsPreview.componentInstance.errors).toBe(mockGeneratedProductErrors);
        });

        it(`should bound '@Input(productsName)' to <mp-concrete-products-preview> component 'name' input`, () => {
            component.productsName = mockProductsName;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(By.css('mp-concrete-products-preview'));

            expect(concreteProductsPreview.componentInstance.name).toBe(mockProductsName);
        });

        it(`should bound '@Input(attributesName)' to <mp-concrete-product-attributes-selector> component 'name' input`, () => {
            component.attributesName = mockAttributesName;
            fixture.detectChanges();

            const concreteProductAttributesSelector = fixture.debugElement.query(
                By.css('mp-concrete-product-attributes-selector'),
            );

            expect(concreteProductAttributesSelector.componentInstance.name).toBe(mockAttributesName);
        });

        it(`should bound '@Input(attributesPlaceholder)' to <mp-concrete-product-attributes-selector> component 'placeholder' input`, () => {
            component.attributesPlaceholder = mockAttributesPlaceholder;
            fixture.detectChanges();

            const concreteProductAttributesSelector = fixture.debugElement.query(
                By.css('mp-concrete-product-attributes-selector'),
            );

            expect(concreteProductAttributesSelector.componentInstance.placeholder).toBe(mockAttributesPlaceholder);
        });

        it(`should update '<mp-concrete-products-preview>' component 'attributes' input when 'selectedAttributesChange' event emitted`, () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const concreteProductAttributesSelector = fixture.debugElement.query(
                By.css('mp-concrete-product-attributes-selector'),
            );
            const concreteProductsPreview = fixture.debugElement.query(By.css('mp-concrete-products-preview'));

            concreteProductAttributesSelector.triggerEventHandler('selectedAttributesChange', mockSelectedAttributes);
            fixture.detectChanges();

            expect(concreteProductsPreview.componentInstance.attributes).toBe(mockSelectedAttributes);
        });
    });
});
