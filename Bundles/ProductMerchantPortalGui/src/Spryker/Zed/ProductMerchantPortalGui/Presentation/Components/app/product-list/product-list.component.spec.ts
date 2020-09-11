import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { ProductListComponent } from './product-list.component';
import { NO_ERRORS_SCHEMA } from '@angular/core';

describe('ProductListComponent', () => {
    let component: ProductListComponent;
    let fixture: ComponentFixture<ProductListComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ProductListComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ProductListComponent);
        component = fixture.componentInstance;
    });

    it('should render `mp-product-list-table` component', () => {
        const offerTableComponent = fixture.debugElement.query(By.css('mp-product-list-table'));

        expect(offerTableComponent).toBeTruthy();
    });

    it('should render `spy-headline` component', () => {
        const headlineComponent = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineComponent).toBeTruthy();
    });

    it('should bind @Input(tableConfig) to `config` of `mp-product-list-table` component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const offerTableComponent = fixture.debugElement.query(By.css('mp-product-list-table'));

        component.tableConfig = mockTableConfig;
        fixture.detectChanges();

        expect(offerTableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bind @Input(tableId) to `tableId` of `mp-product-list-table` component', () => {
        const mockTableId = 'mockTableId';

        const tableComponent = fixture.debugElement.query(By.css('mp-product-list-table'));

        component.tableId = mockTableId;
        fixture.detectChanges();

        expect(tableComponent.properties.tableId).toEqual(mockTableId);
    });
});
