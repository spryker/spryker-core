import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductOfferTableComponent } from './product-offer-table.component';

describe('ProductOfferTableComponent', () => {
  let component: ProductOfferTableComponent;
  let fixture: ComponentFixture<ProductOfferTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProductOfferTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProductOfferTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
