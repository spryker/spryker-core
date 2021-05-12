import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { EditAbstractProductAttributesComponent } from './edit-abstract-product-attributes.component';
import { NO_ERRORS_SCHEMA } from '@angular/core';

describe('EditAbstractProductAttributesComponent', () => {
    let component: EditAbstractProductAttributesComponent;
    let fixture: ComponentFixture<EditAbstractProductAttributesComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [EditAbstractProductAttributesComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(EditAbstractProductAttributesComponent);
        component = fixture.componentInstance;
    });

    it('should render `spy-table` component', () => {
        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        expect(tableComponent).toBeTruthy();
    });

    it('should bind @Input(config) to `config` of `spy-table` component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        component.config = mockTableConfig;
        fixture.detectChanges();

        expect(tableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bind @Input(tableId) to `tableId` of `spy-table` component', () => {
        const mockTableId = 'mockTableId';

        const tableComponent = fixture.debugElement.query(By.css('spy-table'));

        component.tableId = mockTableId;
        fixture.detectChanges();

        expect(tableComponent.properties.tableId).toEqual(mockTableId);
    });
});
