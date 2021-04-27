import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, fakeAsync, TestBed, tick } from '@angular/core/testing';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { By } from '@angular/platform-browser';
import { ConcreteProductsPreviewComponent } from './concrete-products-preview.component';
import { ConcreteProductSkuGeneratorFactoryService } from '../../services/concrete-product-sku-generator-factory.service';
import { ConcreteProductNameGeneratorFactoryService } from '../../services/concrete-product-name-generator-factory.service';
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
                }
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                }
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
                }
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                }
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
        <mp-concrete-products-preview [name]="name" [attributes]="attributes">
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
}

describe('ConcreteProductsPreviewComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ScrollingModule],
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
        it('should render `noData` element with default `no-data-text` slot if `@Input(attributes)` not exists', () => {
            component.attributes = [];
            fixture.detectChanges();

            const noDataElement = fixture.debugElement.query(By.css('.mp-concrete-products-preview__no-data'));

            const noDataText = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__no-data [no-data-text]'),
            );

            expect(noDataElement).toBeTruthy();
            expect(noDataText.nativeElement.textContent).toBe('No concretes created yet');
        });

        it('should render <spy-chips> component with default `total-text` slot to the `.mp-concrete-products-preview__header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const headerChips = fixture.debugElement.query(By.css('.mp-concrete-products-preview__header spy-chips'));
            const headerChipsTotalText = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header spy-chips [total-text]'),
            );

            expect(headerChips.nativeElement.textContent).toContain('2 to be created');
            expect(headerChipsTotalText.nativeElement.textContent).toBe('to be created');
        });

        it('should render <spy-checkbox> component with default `auto-sku-text` slot to the `.mp-concrete-products-preview__header-checkboxes` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const headerCheckbox = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header-checkboxes spy-checkbox'),
            );
            const headerCheckboxSkuText = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header spy-checkbox [auto-sku-text]'),
            );

            expect(headerCheckbox).toBeTruthy();
            expect(headerCheckboxSkuText.nativeElement.textContent).toBe('Autogenerate SKUs');
        });

        it('should render <spy-checkbox> component with default `auto-name-text` slot to the `.mp-concrete-products-preview__header-checkboxes` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const headerCheckbox = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header-checkboxes spy-checkbox'),
            );
            const headerCheckboxNameText = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__header spy-checkbox [auto-name-text]'),
            );

            expect(headerCheckbox).toBeTruthy();
            expect(headerCheckboxNameText.nativeElement.textContent).toBe('Same Name as Abstract Product');
        });

        it('should render default `col-attr-name` slot to the `.mp-concrete-products-preview__table-header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const tableHeaderAttrName = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__table-header [col-attr-name]'),
            );

            expect(tableHeaderAttrName.nativeElement.textContent).toBe('Super attribute value');
        });

        it('should render default `col-sku-name` slot to the `.mp-concrete-products-preview__table-header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const tableHeaderSkuName = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__table-header [col-sku-name]'),
            );

            expect(tableHeaderSkuName.nativeElement.textContent).toBe('SKU');
        });

        it('should render default `col-name-name` slot to the `.mp-concrete-products-preview__table-header` element', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const tableHeaderNameDefault = fixture.debugElement.query(
                By.css('.mp-concrete-products-preview__table-header [col-name-name]'),
            );

            expect(tableHeaderNameDefault.nativeElement.textContent).toBe('Name default');
        });

        it('should render <cdk-virtual-scroll-viewport> component', () => {
            component.attributes = mockAttributes;
            fixture.detectChanges();

            const cdkVirtualScrollViewport = fixture.debugElement.query(By.css('cdk-virtual-scroll-viewport'));

            expect(cdkVirtualScrollViewport).toBeTruthy();
        });

        it('should render <spy-input> component to the `.mp-concrete-products-preview__table-row-sku` element', fakeAsync(() => {
            component.attributes = mockAttributes;
            fixture.autoDetectChanges();
            tick(500);

            const skuInput = fixture.debugElement.query(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-sku spy-input'),
            );

            expect(skuInput).toBeTruthy();
        }));

        it('should render <spy-input> component to the `.mp-concrete-products-preview__table-row-name` element', fakeAsync(() => {
            component.attributes = mockAttributes;
            fixture.autoDetectChanges();
            tick(500);

            const skuInput = fixture.debugElement.query(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-name spy-input'),
            );

            expect(skuInput).toBeTruthy();
        }));

        it('should render <spy-button> with <spy-input> components to the `.mp-concrete-products-preview__table-row-name` element', fakeAsync(() => {
            component.attributes = mockAttributes;
            fixture.autoDetectChanges();
            tick(500);

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
        it('should render <input type="hidden"> element if `@Input(name) exists`', () => {
            component.attributes = mockAttributes;
            component.name = mockName;
            fixture.detectChanges();

            const hiddenInput = fixture.debugElement.query(By.css('input[type=hidden]'));

            expect(hiddenInput).toBeTruthy();
            expect(hiddenInput.properties.name).toBe(mockName);
            expect(hiddenInput.properties.value.replace(/\s/g, '')).toBe(JSON.stringify(mockGeneratedProducts));
        });

        it('should render attribute names of generated products', fakeAsync(() => {
            const mockAttrNames = {
                firstRow: 'name11  /  name21',
                secondRow: 'name12  /  name21',
            };

            component.attributes = mockAttributes;
            fixture.autoDetectChanges();
            tick(500);

            const attrNames = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-attr'),
            );

            expect(attrNames[0].nativeElement.textContent.trim()).toBe(mockAttrNames.firstRow);
            expect(attrNames[1].nativeElement.textContent.trim()).toBe(mockAttrNames.secondRow);
        }));

        it('`Autogenerate SKUs` checkbox should set generated value to inputs', fakeAsync(() => {
            const mockSkuValues = {
                firstRow: 'mockId-0',
                secondRow: 'mockId-1',
            };

            component.attributes = mockAttributes;
            fixture.autoDetectChanges();
            tick(500);

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

            expect(skuInputs[0].properties.value).toBe('');
            expect(skuInputs[1].properties.value).toBe('');

            headerCheckboxes[0].triggerEventHandler('checkedChange', true);
            fixture.detectChanges();

            const updatedSkuInputs = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-sku spy-input'),
            );

            expect(updatedSkuInputs[0].properties.value).toBe(mockSkuValues.firstRow);
            expect(skuGeneratorFactory.generator.generate).toHaveBeenCalledWith(mockSkuValues.firstRow);
            expect(skuGeneratorFactory.generator.generate).not.toHaveBeenCalledWith(mockSkuValues.secondRow);
            expect(updatedSkuInputs[1].properties.value).toBe(mockSkuValues.secondRow);
        }));

        it('`Same Name as Abstract Product` checkbox should set generated value to inputs', fakeAsync(() => {
            const mockNameValues = {
                firstRow: 'mockId-0',
                secondRow: 'mockId-1',
            };

            component.attributes = mockAttributes;
            fixture.autoDetectChanges();
            tick(500);

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

            expect(nameInputs[0].properties.value).toBe('');
            expect(nameInputs[1].properties.value).toBe('');

            headerCheckboxes[1].triggerEventHandler('checkedChange', true);
            fixture.detectChanges();

            const updatedNameInputs = fixture.debugElement.queryAll(
                By.css('cdk-virtual-scroll-viewport .mp-concrete-products-preview__table-row-name spy-input'),
            );

            expect(updatedNameInputs[0].properties.value).toBe(mockNameValues.firstRow);
            expect(nameGeneratorFactory.generator.generate).toHaveBeenCalledWith(mockNameValues.firstRow);
            expect(nameGeneratorFactory.generator.generate).not.toHaveBeenCalledWith(mockNameValues.secondRow);
            expect(updatedNameInputs[1].properties.value).toBe(mockNameValues.secondRow);
        }));
    });
});
