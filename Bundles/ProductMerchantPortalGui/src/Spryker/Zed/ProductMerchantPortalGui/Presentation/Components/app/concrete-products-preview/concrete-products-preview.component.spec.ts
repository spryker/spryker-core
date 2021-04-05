import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, fakeAsync, TestBed, tick } from '@angular/core/testing';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { By } from '@angular/platform-browser';
import { ConcreteProductsPreviewComponent } from './concrete-products-preview.component';
import { ConcreteProductGeneratorDataService } from '../../services/concrete-product-generator-data.service';

const mockName = 'Name';
const mockAttributes = [
    {
        title: "name1",
        value: "value1",
        values: [
            {
                title: "name11",
                value: "value11"
            },
            {
                title: "name12",
                value: "value12"
            }
        ]
    },
    {
        title: "name2",
        value: "value2",
        values: [
            {
                title: "name21",
                value: "value21"
            }
        ]
    }
];

@Component({
    selector: 'spy-test',
    template: `
        <mp-concrete-products-preview
            [name]="name"
            [attributes]="attributes">
            <span total-text>to be created</span>
            <span auto-sku-text>Autogenerate SKUs</span>
            <span auto-name-text>Same Name as Abstract Product</span>
            <span col-attr-name>Super attribute value</span>
            <span col-sku-name>SKU</span>
            <span col-name-name>Name default</span>
            <span no-data-text>No concretes created yet</span>
        </mp-concrete-products-preview>
    `
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
            providers: [ConcreteProductGeneratorDataService],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render `noData` element with default `no-data-text` slot if `@Input(attributes)` not exists', () => {
        component.attributes = [];
        fixture.detectChanges();

        const noDataElement = fixture.debugElement.query(
            By.css('.mp-concrete-products-preview__no-data'),
        );

        const noDataText = fixture.debugElement.query(
            By.css('.mp-concrete-products-preview__no-data [no-data-text]'),
        );

        expect(noDataElement).toBeTruthy();
        expect(noDataText.nativeElement.textContent).toBe('No concretes created yet');
    });

    it('should render <spy-chips> component with default `total-text` slot to the `.mp-concrete-products-preview__header` element', () => {
        component.attributes = mockAttributes;
        fixture.detectChanges();

        const headerChips = fixture.debugElement.query(
            By.css('.mp-concrete-products-preview__header spy-chips'),
        );
        const headerChipsTotalText = fixture.debugElement.query(
            By.css('.mp-concrete-products-preview__header spy-chips [total-text]'),
        );

        expect(headerChips).toBeTruthy();
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

        const cdkVirtualScrollViewport = fixture.debugElement.query(
            By.css('cdk-virtual-scroll-viewport'),
        );

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

    it('should render <input type="hidden"> element if `@Input(name) exists`', () => {
        component.attributes = mockAttributes;
        component.name = mockName;
        fixture.detectChanges();

        const hiddenInput = fixture.debugElement.query(
            By.css('input[type=hidden]'),
        );

        expect(hiddenInput).toBeTruthy();
    });
});
