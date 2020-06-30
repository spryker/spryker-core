import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { ProductOfferComponent } from './product-offer.component';
import { NO_ERRORS_SCHEMA } from '@angular/core';

describe('ProductOfferComponent', () => {
    let component: ProductOfferComponent;
    let fixture: ComponentFixture<ProductOfferComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ProductOfferComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ProductOfferComponent);
        component = fixture.componentInstance;
    });

    it('should render `mp-product-offer-table` component', () => {
        const offerTableComponent = fixture.debugElement.query(By.css('mp-product-offer-table'));

        expect(offerTableComponent).toBeTruthy();
    });

    it('should render @Input(title) inside `h1` element', () => {
        const mockTitle = 'Test Title';
        const headingContainer = fixture.debugElement.query(By.css('spy-headline h1'));

        component.title = mockTitle;
        fixture.detectChanges();

        expect(headingContainer.nativeElement.textContent).toContain(mockTitle);
    });

    it('should bind @Input(tableConfig) to `config` of `mp-product-offer-table` component', () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        } as any;
        const offerTableComponent = fixture.debugElement.query(By.css('mp-product-offer-table'));

        component.tableConfig = mockTableConfig;
        fixture.detectChanges();

        expect(offerTableComponent.properties.config).toEqual(mockTableConfig);
    });
});
