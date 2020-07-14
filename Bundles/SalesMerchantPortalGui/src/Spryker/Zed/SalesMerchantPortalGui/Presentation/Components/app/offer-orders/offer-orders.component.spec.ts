import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { OfferOrdersComponent } from './offer-orders.component';
import { NO_ERRORS_SCHEMA } from '@angular/core';

describe('OfferOrdersComponent', () => {
    let component: OfferOrdersComponent;
    let fixture: ComponentFixture<OfferOrdersComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [OfferOrdersComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(OfferOrdersComponent);
        component = fixture.componentInstance;
    });

    it('should render `mp-offer-orders-table` component', () => {
        const listTableComponent = fixture.debugElement.query(By.css('mp-offer-orders-table'));

        expect(listTableComponent).toBeTruthy();
    });

    it('should render @Input(title) inside `h1` element', () => {
        const mockTitle = 'Test Title';
        const headingContainer = fixture.debugElement.query(By.css('h1'));

        component.title = mockTitle;
        fixture.detectChanges();

        expect(headingContainer.nativeElement.textContent).toContain(mockTitle);
    });

    it('should bind @Input(tableConfig) to `config` of `mp-offer-orders-table` component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const listTableComponent = fixture.debugElement.query(By.css('mp-offer-orders-table'));

        component.tableConfig = mockTableConfig;
        fixture.detectChanges();

        expect(listTableComponent.properties.config).toEqual(mockTableConfig);
    });
});
