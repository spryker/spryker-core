import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProductAttributesSelectorComponent } from './product-attributes-selector.component';

describe('ProductAttributesSelectorComponent', () => {
    let component: ProductAttributesSelectorComponent;
    let fixture: ComponentFixture<ProductAttributesSelectorComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ProductAttributesSelectorComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ProductAttributesSelectorComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
