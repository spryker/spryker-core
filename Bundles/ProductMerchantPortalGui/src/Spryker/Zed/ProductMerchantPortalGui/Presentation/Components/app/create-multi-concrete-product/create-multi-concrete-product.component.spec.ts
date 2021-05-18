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
            <span title class="projected-title">Name</span>
            <span action class="projected-action">Button</span>

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

        it('should render projected title to the `.mp-create-multi-concrete-product__header` element', () => {
            const projectedTitle = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__header .projected-title'),
            );

            expect(projectedTitle.nativeElement.textContent).toBe('Name');
        });

        it('should render projected action to the `.mp-create-multi-concrete-product__header` element', () => {
            const projectedAction = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__header .projected-action'),
            );

            expect(projectedAction.nativeElement.textContent).toBe('Button');
        });

        describe('<mp-product-attributes-selector> component', () => {
            it('should render <mp-product-attributes-selector> component to the `.mp-create-multi-concrete-product__content` element', () => {
                const productAttributesSelector = fixture.debugElement.query(
                    By.css('.mp-create-multi-concrete-product__content mp-product-attributes-selector'),
                );

                expect(productAttributesSelector).toBeTruthy();
            });

            it('should render default `selector-col-attr-name` slot', () => {
                const selectorColAttrName = fixture.debugElement.query(
                    By.css('mp-product-attributes-selector [selector-col-attr-name]'),
                );

                expect(selectorColAttrName.nativeElement.textContent).toBe('Super Attribute');
            });

            it('should render default `selector-col-attr-values-name` slot', () => {
                const selectorColAttrValuesName = fixture.debugElement.query(
                    By.css('mp-product-attributes-selector [selector-col-attr-values-name]'),
                );

                expect(selectorColAttrValuesName.nativeElement.textContent).toBe('Values');
            });

            it('should render default `selector-btn-attr-add-name` slot', () => {
                const selectorBtnAttrAddName = fixture.debugElement.query(
                    By.css('mp-product-attributes-selector [selector-btn-attr-add-name]'),
                );

                expect(selectorBtnAttrAddName.nativeElement.textContent).toBe('Add');
            });
        });

        it('should render default `preview-text` slot to the `.mp-create-multi-concrete-product__preview-title` element', () => {
            const previewText = fixture.debugElement.query(
                By.css('.mp-create-multi-concrete-product__preview-title [preview-text]'),
            );

            expect(previewText.nativeElement.textContent).toBe('Concrete Products’ Preview');
        });

        describe('<mp-concrete-products-preview> component', () => {
            it('should render <mp-concrete-products-preview> component to the `.mp-create-multi-concrete-product__content` element', () => {
                const concreteProductsPreview = fixture.debugElement.query(
                    By.css('.mp-create-multi-concrete-product__content mp-concrete-products-preview'),
                );

                expect(concreteProductsPreview).toBeTruthy();
            });

            it('should render default `preview-total-text` slot', () => {
                const previewTotalText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-total-text]'),
                );

                expect(previewTotalText.nativeElement.textContent).toBe('to be created');
            });

            it('should render default `preview-auto-sku-text` slot', () => {
                const previewAutoSkuText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-sku-text]'),
                );

                expect(previewAutoSkuText.nativeElement.textContent).toBe('Autogenerate SKUs');
            });

            it('should render default `preview-auto-name-text` slot', () => {
                const previewAutoNameText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-name-text]'),
                );

                expect(previewAutoNameText.nativeElement.textContent).toBe('Same Name as Abstract Product');
            });

            it('should render default `preview-auto-name-text` slot', () => {
                const previewAutoNameText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-auto-name-text]'),
                );

                expect(previewAutoNameText.nativeElement.textContent).toBe('Same Name as Abstract Product');
            });

            it('should render default `preview-col-attr-name` slot', () => {
                const previewColAttrName = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-attr-name]'),
                );

                expect(previewColAttrName.nativeElement.textContent).toBe('Super attribute value');
            });

            it('should render default `preview-col-sku-name` slot', () => {
                const previewColSkuName = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-sku-name]'),
                );

                expect(previewColSkuName.nativeElement.textContent).toBe('SKU');
            });

            it('should render default `preview-col-name-name` slot', () => {
                const previewColNameName = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-col-name-name]'),
                );

                expect(previewColNameName.nativeElement.textContent).toBe('Name default');
            });

            it('should render default `preview-no-data-text` slot', () => {
                const previewNoDataText = fixture.debugElement.query(
                    By.css('mp-concrete-products-preview [preview-no-data-text]'),
                );

                expect(previewNoDataText.nativeElement.textContent).toBe('No concretes created yet');
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

            productAttributesSelector.componentInstance.selectedAttributesChange.emit(mockSelectedAttributes);
            fixture.detectChanges();

            expect(concreteProductsPreview.componentInstance.attributes).toBe(mockSelectedAttributes);
        });
    });
});
