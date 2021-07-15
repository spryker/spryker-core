import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, fakeAsync, TestBed, tick } from '@angular/core/testing';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { InvokeModule } from '@spryker/utils';
import { By } from '@angular/platform-browser';
import { ConcreteProductsPreviewComponent } from './concrete-products-preview.component';
import { ConcreteProductSkuGeneratorFactoryService } from '../../services/concrete-product-sku-generator-factory.service';
import { ConcreteProductNameGeneratorFactoryService } from '../../services/concrete-product-name-generator-factory.service';
import { ProductAttributesFinderService } from '../../services/product-attributes-finder.service';
import { IdGenerator } from '../../services/types';

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

class MockGenerator implements IdGenerator {
    index = 0;
    generate = jest.fn().mockImplementation(() => {
        return `mockId-${this.index++}`;
    });
}

class MockGeneratorFactory {
    generator = new MockGenerator();
    create() {
        return this.generator;
    }
}

@Component({
    selector: 'spy-test',
    template: `
        <mp-concrete-products-preview
            [name]="name"
            [attributes]="attributes"
            [generatedProducts]="generatedProducts"
            [errors]="errors"
            [existingProducts]="existingProducts"
        >
            <span total-text>to be created</span>
            <span auto-sku-text>Autogenerate SKUs</span>
            <span auto-name-text>Same Name as Abstract Product</span>
            <span col-attr-name>Super attribute value</span>
            <span col-sku-name>SKU</span>
            <span col-name-name>Name default</span>
            <span no-data-text>No concretes created yet</span>
        </mp-concrete-products-preview>
    `,
})
class TestComponent {
    name: string;
    attributes: any;
    generatedProducts: any = [];
    errors: any;
    existingProducts: any;
}

describe('ConcreteProductsPreviewComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ScrollingModule, InvokeModule],
            declarations: [ConcreteProductsPreviewComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        })
            .overrideComponent(ConcreteProductsPreviewComponent, {
                set: {
                    providers: [
                        {
                            provide: ConcreteProductSkuGeneratorFactoryService,
                            useClass: MockGeneratorFactory,
                        },
                        {
                            provide: ConcreteProductNameGeneratorFactoryService,
                            useClass: MockGeneratorFactory,
                        },
                        ProductAttributesFinderService,
                    ],
                },
            })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    describe('Slots and components', () => {
        it('should render `noData` element with `no-data-text` slot if `@Input(attributes)` not exists', () => {
            component.attributes = [];
            fixture.detectChanges();

            const noDataElement = fixture.debugElement.query(By.css('.mp-concrete-products-preview__no-data'));

            const noDataTextSlot = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__no-data [no-data-text]'),
            );

            expect(noDataElement).toBeTruthy();
            expect(noDataTextSlot).toBeTruthy();
        });

        it('should render <spy-chips> component with `total-text` slot to the `.mp-concrete-products-preview__header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const headerChips = fixture.debugElement.query(By.css('.mp-concrete-products-preview__header spy-chips'));
            const totalTextSlot = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header spy-chips [total-text]'),
            );

            expect(headerChips).toBeTruthy();
            expect(headerChips.nativeElement.textContent).toContain('2 to be created');
            expect(totalTextSlot).toBeTruthy();
        });

        it('should render <spy-checkbox> component with `auto-sku-text` slot to the `.mp-concrete-products-preview__header-checkboxes` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const headerCheckboxSku = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header-checkboxes spy-checkbox'),
            );
            const autoSkuTextSlot = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header spy-checkbox [auto-sku-text]'),
            );

            expect(headerCheckboxSku).toBeTruthy();
            expect(autoSkuTextSlot).toBeTruthy();
        });

        it('should render <spy-checkbox> component with `auto-name-text` slot to the `.mp-concrete-products-preview__header-checkboxes` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const headerCheckboxName = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header-checkboxes spy-checkbox'),
            );
            const autoNameTextSlot = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header spy-checkbox [auto-name-text]'),
            );

            expect(headerCheckboxName).toBeTruthy();
            expect(autoNameTextSlot).toBeTruthy();
        });

        it('should render `col-attr-name` slot to the `.mp-concrete-products-preview__table-header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const colAttrNameSlot = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__table-header [col-attr-name]'),
            );

            expect(colAttrNameSlot).toBeTruthy();
        });

        it('should render `col-sku-name` slot to the `.mp-concrete-products-preview__table-header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const colSkuNameSlot = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__table-header [col-sku-name]'),
            );

            expect(colSkuNameSlot).toBeTruthy();
        });

        it('should render `col-name-name` slot to the `.mp-concrete-products-preview__table-header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const colNameSlot = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__table-header [col-name-name]'),
            );

            expect(colNameSlot).toBeTruthy();
        });

        it('should render <cdk-virtual-scroll-viewport> component', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const cdkVirtualScrollViewport = fixture.debugElement.query(By.css('cdk-virtual-scroll-viewport'));

            expect(cdkVirtualScrollViewport).toBeTruthy();
        });

        it('should render <spy-input> component to the `.mp-concrete-products-preview__table-row-sku` element', fakeAsync(() => {
            component.attributes = mockAttributes;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const skuInput = fixture.debugElement.query(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-sku spy-input'),
            );

            expect(skuInput).toBeTruthy();
        }));

        it('should render <spy-input> component to the `.mp-concrete-products-preview__table-row-name` element', fakeAsync(() => {
            component.attributes = mockAttributes;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const skuInput = fixture.debugElement.query(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-name spy-input'),
            );

            expect(skuInput).toBeTruthy();
        }));

        it('should render <spy-button> with <spy-input> components to the `.mp-concrete-products-preview__table-row-name` element', fakeAsync(() => {
            component.attributes = mockAttributes;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const removeButton = fixture.debugElement.query(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-name spy-button'),
            );
            const removeButtonIcon = fixture.debugElement.query(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-name spy-button spy-icon'),
            );

            expect(removeButton).toBeTruthy();
            expect(removeButtonIcon).toBeTruthy();
        }));
    });

    describe('Host functionality', () => {
        it('should render hidden <input> element with serialized generated products if `@Input(name)` exists', () => {
            component.attributes = mockAttributes;
            component.name = mockName;
            fixture.detectChanges();

            const hiddenInput = fixture.debugElement.query(By.css('input[type=hidden]'));

            expect(hiddenInput).toBeTruthy();
            expect(hiddenInput.properties.name).toBe(mockName);
            expect(JSON.parse(hiddenInput.properties.value)).toEqual(mockGeneratedProducts);
        });

        it('should render attribute names of generated products', fakeAsync(() => {
            const expectedAttrNames = {
                firstRow: 'name11  /  name21',
                secondRow: 'name12  /  name21',
            };

            component.attributes = mockAttributes;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const attrNames = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-attr'),
            );

            expect(attrNames[0].nativeElement.textContent.trim()).toBe(expectedAttrNames.firstRow);
            expect(attrNames[1].nativeElement.textContent.trim()).toBe(expectedAttrNames.secondRow);
        }));

        it('`Autogenerate SKUs` checkbox should set generated value to inputs', fakeAsync(() => {
            const expectedSkuValues = {
                firstRow: 'mockId-0',
                secondRow: 'mockId-1',
            };

            component.attributes = mockAttributes;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const headerCheckboxes = fixture.debugElement.queryAll(
                By.css('.mp-concrete-products-preview__header-checkboxes spy-checkbox'),
            );
            const skuInputs = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-sku spy-input'),
            );
            const componentElem = fixture.debugElement.query(By.directive(ConcreteProductsPreviewComponent));
            const skuGeneratorFactory = (componentElem.injector.get(
                ConcreteProductSkuGeneratorFactoryService,
            ) as any) as MockGeneratorFactory;

            headerCheckboxes[0].triggerEventHandler('checkedChange', true);
            fixture.detectChanges();

            expect(skuInputs[0].properties.value).toBe(expectedSkuValues.firstRow);
            expect(skuGeneratorFactory.generator.generate).toHaveBeenCalledWith(expectedSkuValues.firstRow);
            expect(skuGeneratorFactory.generator.generate).not.toHaveBeenCalledWith(expectedSkuValues.secondRow);
            expect(skuInputs[1].properties.value).toBe(expectedSkuValues.secondRow);

            headerCheckboxes[0].triggerEventHandler('checkedChange', false);
            fixture.detectChanges();

            const updatedSkuInputs = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-sku spy-input'),
            );

            expect(updatedSkuInputs[0].properties.value).toBe('');
            expect(updatedSkuInputs[1].properties.value).toBe('');
        }));

        it('`Same Name as Abstract Product` checkbox should set generated value to inputs', fakeAsync(() => {
            const expectedNameValues = {
                firstRow: 'mockId-0',
                secondRow: 'mockId-1',
            };

            component.attributes = mockAttributes;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const headerCheckboxes = fixture.debugElement.queryAll(
                By.css('.mp-concrete-products-preview__header-checkboxes spy-checkbox'),
            );
            const nameInputs = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-name spy-input'),
            );
            const componentElem = fixture.debugElement.query(By.directive(ConcreteProductsPreviewComponent));
            const nameGeneratorFactory = (componentElem.injector.get(
                ConcreteProductNameGeneratorFactoryService,
            ) as any) as MockGeneratorFactory;

            headerCheckboxes[1].triggerEventHandler('checkedChange', true);
            fixture.detectChanges();

            expect(nameInputs[0].properties.value).toBe(expectedNameValues.firstRow);
            expect(nameGeneratorFactory.generator.generate).toHaveBeenCalledWith(expectedNameValues.firstRow);
            expect(nameGeneratorFactory.generator.generate).not.toHaveBeenCalledWith(expectedNameValues.secondRow);
            expect(nameInputs[1].properties.value).toBe(expectedNameValues.secondRow);

            headerCheckboxes[1].triggerEventHandler('checkedChange', false);
            fixture.detectChanges();

            const updatedNameInputs = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-name spy-input'),
            );

            expect(updatedNameInputs[0].properties.value).toBe('');
            expect(updatedNameInputs[1].properties.value).toBe('');
        }));

        it('should bound `@Input(errors)` to the input `error` of <spy-form-item> component', fakeAsync(() => {
            component.attributes = mockAttributes;
            component.generatedProducts = mockGeneratedProducts;
            component.errors = mockGeneratedProductErrors;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const skuFormItems = fixture.debugElement.queryAll(
                By.css('.mp-concrete-products-preview__table-row-sku spy-form-item'),
            );
            const nameFormItems = fixture.debugElement.queryAll(
                By.css('.mp-concrete-products-preview__table-row-name spy-form-item'),
            );

            expect(skuFormItems[0].properties.error).toBe(mockGeneratedProductErrors[0].errors.sku);
            expect(nameFormItems[0].properties.error).toBe(mockGeneratedProductErrors[0].errors.name);
        }));

        it('should update `@Input(errors)` after removing item with errors', fakeAsync(() => {
            component.attributes = mockAttributes;
            component.generatedProducts = mockGeneratedProducts;
            component.errors = mockGeneratedProductErrors;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const button = fixture.debugElement.query(By.css('.mp-concrete-products-preview__table-row-button'));
            button.triggerEventHandler('click', null);
            fixture.detectChanges();

            const formItem = fixture.debugElement.query(By.css('spy-form-item'));

            expect(formItem.properties.error).toBeFalsy();
        }));

        it('should excludes existing products according to `@Input(existingProducts)`', fakeAsync(() => {
            component.attributes = mockAttributes;
            component.existingProducts = mockExistingProducts;
            fixture.detectChanges();
            tick();
            fixture.detectChanges();

            const existVariant = `${mockExistingProducts[0].superAttributes[0].attribute.name}  /  ${mockExistingProducts[0].superAttributes[1].attribute.name}`;
            const variants = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-attr'),
            );

            expect(variants.some((item) => item.nativeElement.textContent.trim() === existVariant)).toBeFalsy();
        }));
    });
});
