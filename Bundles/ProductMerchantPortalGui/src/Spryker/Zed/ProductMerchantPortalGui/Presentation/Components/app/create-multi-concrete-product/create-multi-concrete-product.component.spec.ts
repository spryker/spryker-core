import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateMultiConcreteProductComponent } from './create-multi-concrete-product.component';

describe('CreateMultiConcreteProductComponent', () => {
    let component: CreateMultiConcreteProductComponent;
    let fixture: ComponentFixture<CreateMultiConcreteProductComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [CreateMultiConcreteProductComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(CreateMultiConcreteProductComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
