import { Component, EventEmitter, Input, NO_ERRORS_SCHEMA, Output } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { CreateMultiConcreteProductComponent } from './create-multi-concrete-product.component';

const mockAttributesName = 'attributesName';
const mockProductsName = 'productsName';
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
    selector: `spy-test`,
    template: `
        <mp-create-multi-concrete-product>
            <span title>Name</span>
            <span action>Button</span>

            <span selector-col-attr-name>Super Attribute</span>
            <span selector-col-attr-values-name>Values</span>
            <span selector-btn-attr-add-name>Add</span>

            <h3 preview-text>Concrete Products’ Preview</h3>

            <span preview-total-text>to be created</span>
            <span preview-auto-sku-text>Autogenerate SKUs</span>
            <span preview-auto-name-text>Same Name as Abstract Product</span>
            <span preview-col-attr-name>Super attribute value</span>
            <span preview-col-sku-name>SKU</span>
            <span preview-col-name-name>Name default</span>
            <span preview-no-data-text>No concretes created yet</span>
        </mp-create-multi-concrete-product>
    `,
})
class TestComponent {}

@Component({
    selector: `mp-product-attributes-selector`,
    template: '',
})
class MockProductAttributesSelectorComponent {
    @Input() attributes: any;
    @Input() selectedAttributes: any;
    @Input() name: any;
    @Output() selectedAttributesChange = new EventEmitter<any[]>();
}

@Component({
    selector: `mp-concrete-products-preview`,
    template: '',
})
class MockConcreteProductsPreviewComponent {
    @Input() attributes: any;
    @Input() generatedProducts: any;
    @Input() errors: any;
    @Input() name: any;
}

describe('CreateMultiConcreteProductComponent', () => {
    describe('Slots and Components', () => {
        let component: TestComponent;
        let fixture: ComponentFixture<TestComponent>;

        beforeEach(async(() => {
            TestBed.configureTestingModule({
                declarations: [CreateMultiConcreteProductComponent, TestComponent],
                schemas: [NO_ERRORS_SCHEMA],
            }).compileComponents();
        }));

        beforeEach(() => {
            fixture = TestBed.createComponent(TestComponent);
            component = fixture.componentInstance;
        });

        it('should render <spy-headline> component', () => {
            const headlineElem = fixture.debugElement.query(By.css('spy-headline'));

            expect(headlineElem).toBeTruthy();
        });

        it('should render `title` slot to the `.mp-create-multi-concrete-product__header` element', () => {
            const titleSlot = fixture.debugElement.query(By.css('.mp-create-multi-concrete-product__header [title]'));

            expect(titleSlot).toBeTruthy();
        });

        it('should render `action` slot to the `.mp-create-multi-concrete-product__header` element', () => {
            const actionSlot = fixture.debugElement.query(By.css('.mp-create-multi-concrete-product__header [action]'));

            expect(actionSlot).toBeTruthy();
        });

        describe('<mp-product-attributes-selector> component', () => {
            it('should render <mp-product-attributes-selector> component to the `.mp-create-multi-concrete-product__content` element', () => {
                const productAttributesSelector = fixture.debugElement.query(
                    By.css('.mp-create-multi-concrete-product__content mp-product-attributes-selector'),
                );

                expect(productAttributesSelector).toBeTruthy();
            });

            it('should render `selector-col-attr-name` slot', () => {
                const selectorColAttrNameSlot = fixture.debugElement.query(
                    By.css('mp-product-attributes-selector [selector-col-attr-name]'),
                );

                expect(selectorColAttrNameSlot).toBeTruthy();
            });

            it('should render `selector-col-attr-values-name` slot', () => {
                const selectorColAttrValuesNameSlot = fixture.debugElement.query(
                    By.css('mp-product-attributes-selector [selector-col-attr-values-name]'),
                );

                expect(selectorColAttrValuesNameSlot).toBeTruthy();
            });

            it('should render `selector-btn-attr-add-name` slot', () => {
                const selectorBtnAttrAddNameSlot = fixture.debugElement.query(
                    By.css('mp-product-attributes-selector [selector-btn-attr-add-name]'),
                );

                expect(selectorBtnAttrAddNameSlot).toBeTruthy();
            });
        });

        it('should render `preview-text` slot to the `.mp-create-multi-concrete-product__preview-title` element', () => {
            const previewTextSlot = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__preview-title [preview-text]'),
            );

            expect(previewTextSlot).toBeTruthy();
        });

        describe('<mp-concrete-products-preview> component', () => {
            it('should render <mp-concrete-products-preview> component to the `.mp-create-multi-concrete-product__content` element', () => {
                const concreteProductsPreview = fixture.debugElement.query(
                    By.css('.mp-create-multi-concrete-product__content mp-concrete-products-preview'),
                );

                expect(concreteProductsPreview).toBeTruthy();
            });

            it('should render `preview-total-text` slot', () => {
                const previewTotalTextSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-total-text]'),
                );

                expect(previewTotalTextSlot).toBeTruthy();
            });

            it('should render `preview-auto-sku-text` slot', () => {
                const previewAutoSkuTextSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-sku-text]'),
                );

                expect(previewAutoSkuTextSlot).toBeTruthy();
            });

            it('should render `preview-auto-name-text` slot', () => {
                const previewAutoNameTextSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-name-text]'),
                );

                expect(previewAutoNameTextSlot).toBeTruthy();
            });

            it('should render `preview-auto-name-text` slot', () => {
                const previewAutoNameTextSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-name-text]'),
                );

                expect(previewAutoNameTextSlot).toBeTruthy();
            });

            it('should render `preview-col-attr-name` slot', () => {
                const previewColAttrNameSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-attr-name]'),
                );

                expect(previewColAttrNameSlot).toBeTruthy();
            });

            it('should render `preview-col-sku-name` slot', () => {
                const previewColSkuNameSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-sku-name]'),
                );

                expect(previewColSkuNameSlot).toBeTruthy();
            });

            it('should render `preview-col-name-name` slot', () => {
                const previewColNameNameSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-name-name]'),
                );

                expect(previewColNameNameSlot).toBeTruthy();
            });

            it('should render `preview-no-data-text` slot', () => {
                const previewNoDataTextSlot = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-no-data-text]'),
                );

                expect(previewNoDataTextSlot).toBeTruthy();
            });
        });
    });

    describe('@Inputs', () => {
        let component: CreateMultiConcreteProductComponent;
        let fixture: ComponentFixture<CreateMultiConcreteProductComponent>;

        beforeEach(async(() => {
            TestBed.configureTestingModule({
                declarations: [
                    CreateMultiConcreteProductComponent,
                    MockProductAttributesSelectorComponent,
                    MockConcreteProductsPreviewComponent,
                ],
                schemas: [NO_ERRORS_SCHEMA],
            }).compileComponents();
        }));

        beforeEach(() => {
            fixture = TestBed.createComponent(CreateMultiConcreteProductComponent);
            component = fixture.componentInstance;
        });

        it('should bound `@Input(attributesName)` to the `name` input of <mp-product-attributes-selector> component', () => {
            component.attributesName = mockAttributesName;
            fixture.detectChanges();

            const productAttributesSelector = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-product-attributes-selector'),
            );

            expect(productAttributesSelector.componentInstance.name).toBe(mockAttributesName);
        });

        it('should bound `@Input(attributes)` to the `attributes` input of <mp-product-attributes-selector> component', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const productAttributesSelector = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-product-attributes-selector'),
            );

            expect(productAttributesSelector.componentInstance.attributes).toBe(mockAttributes);
        });

        it('should bound `@Input(selectedAttributes)` to the `selectedAttributes` input of <mp-product-attributes-selector> component', () => {
            component.selectedAttributes = mockSelectedAttributes;
            fixture.detectChanges();

            const productAttributesSelector = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-product-attributes-selector'),
            );

            expect(productAttributesSelector.componentInstance.selectedAttributes).toBe(mockSelectedAttributes);
        });

        it('should bound `@Input(productsName)` to the `name` input of <mp-concrete-products-preview> component', () => {
            component.productsName = mockProductsName;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-concrete-products-preview'),
            );

            expect(concreteProductsPreview.componentInstance.name).toBe(mockProductsName);
        });

        it('should bound `@Input(selectedAttributes)` to the `attributes` input of <mp-concrete-products-preview> component', () => {
            component.selectedAttributes = mockSelectedAttributes;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-concrete-products-preview'),
            );

            expect(concreteProductsPreview.componentInstance.attributes).toBe(mockSelectedAttributes);
        });

        it('should bound `@Input(generatedProducts)` to the `generatedProducts` input of <mp-concrete-products-preview> component', () => {
            component.generatedProducts = mockGeneratedProducts;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-concrete-products-preview'),
            );

            expect(concreteProductsPreview.componentInstance.generatedProducts).toBe(mockGeneratedProducts);
        });

        it('should bound `@Input(generatedProductErrors)` to the `errors` input of <mp-concrete-products-preview> component', () => {
            component.generatedProductErrors = mockGeneratedProductErrors;
            fixture.detectChanges();

            const concreteProductsPreview = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-concrete-products-preview'),
            );

            expect(concreteProductsPreview.componentInstance.errors).toBe(mockGeneratedProductErrors);
        });

        it('should update `attributes` input of <mp-concrete-products-preview> component when `selectedAttributesChange` event emitted', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const productAttributesSelector = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-product-attributes-selector'),
            );
            const concreteProductsPreview = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__content mp-concrete-products-preview'),
            );

            productAttributesSelector.triggerEventHandler('selectedAttributesChange', mockSelectedAttributes);
            fixture.detectChanges();

            expect(concreteProductsPreview.componentInstance.attributes).toBe(mockSelectedAttributes);
        });
    });
});
