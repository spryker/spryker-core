import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductOfferComponent } from './product-offer.component';

describe('ProductOfferComponent', () => {
    let component: ProductOfferComponent;
    let fixture: ComponentFixture<ProductOfferComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ProductOfferComponent]
        })
            .compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ProductOfferComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
