import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ConcreteProductsPreviewComponent } from './concrete-products-preview.component';

describe('ConcreteProductsPreviewComponent', () => {
    let component: ConcreteProductsPreviewComponent;
    let fixture: ComponentFixture<ConcreteProductsPreviewComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ConcreteProductsPreviewComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ConcreteProductsPreviewComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
