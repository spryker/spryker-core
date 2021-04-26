import { NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { EditConcreteProductPricesComponent } from '../edit-concrete-product-prices/edit-concrete-product-prices.component';

describe('EditAbstractProductAttributesComponent', () => {
    let component: EditConcreteProductPricesComponent;
    let fixture: ComponentFixture<EditConcreteProductPricesComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [EditConcreteProductPricesComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(EditConcreteProductPricesComponent);
        component = fixture.componentInstance;
    });

    it('should render <spy-table> component', () => {
        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        expect(tableComponent).toBeTruthy();
    });

    it('should bind @Input(tableConfig) to `config` of <spy-table> component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        component.tableConfig = mockTableConfig;
        fixture.detectChanges();

        expect(tableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bind @Input(tableId) to `tableId` of <spy-table> component', () => {
        const mockTableId = 'mockTableId';
        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        component.tableId = mockTableId;
        fixture.detectChanges();

        expect(tableComponent.properties.tableId).toEqual(mockTableId);
    });
});
