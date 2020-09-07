import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { SalesOrdersComponent } from './sales-orders.component';
import { NO_ERRORS_SCHEMA } from '@angular/core';

describe('SalesOrdersComponent', () => {
    let component: SalesOrdersComponent;
    let fixture: ComponentFixture<SalesOrdersComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [SalesOrdersComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(SalesOrdersComponent);
        component = fixture.componentInstance;
    });

    it('should render `mp-sales-orders-table` component', () => {
        const listTableComponent = fixture.debugElement.query(By.css('mp-sales-orders-table'));

        expect(listTableComponent).toBeTruthy();
    });

    it('should render `spy-headline` component', () => {
        const headlineComponent = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineComponent).toBeTruthy();
    });

    it('should bind @Input(tableConfig) to `config` of `mp-sales-orders-table` component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const listTableComponent = fixture.debugElement.query(By.css('mp-sales-orders-table'));

        component.tableConfig = mockTableConfig;
        fixture.detectChanges();

        expect(listTableComponent.properties.config).toEqual(mockTableConfig);
    });
});
