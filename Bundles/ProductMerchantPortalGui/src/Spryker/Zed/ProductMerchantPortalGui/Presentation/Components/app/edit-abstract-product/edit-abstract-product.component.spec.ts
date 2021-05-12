import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { EditAbstractProductComponent } from './edit-abstract-product.component';

@Component({
    selector: 'spy-text',
    template: `
        <mp-edit-abstract-product [product]="product">
            <span action class="projected-action"></span>
            <div class="projected-content"></div>
        </mp-edit-abstract-product>
    `,
})
class TestComponent {
    product: any;
}

const mockProduct = {
    name: 'test name',
    sku: 'test sku',
};

describe('EditAbstractProductComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [EditAbstractProductComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render `product.name` in the `.mp-edit-abstract-product__title` element', () => {
        const titleHolder = fixture.debugElement.query(By.css('.mp-edit-abstract-product__title'));

        component.product = mockProduct;
        fixture.detectChanges();

        expect(titleHolder.nativeElement.textContent).toContain(mockProduct.name);
    });

    it('should render `product.sku` in the `.mp-edit-abstract-product__sku` element', () => {
        const skuHolder = fixture.debugElement.query(By.css('.mp-edit-abstract-product__sku'));

        component.product = mockProduct;
        fixture.detectChanges();

        expect(skuHolder.nativeElement.textContent).toContain(mockProduct.sku);
    });

    it('should render default projected content in the `.mp-edit-abstract-product__content` element', () => {
        const projectedContent = fixture.debugElement.query(
            By.css('.mp-edit-abstract-product__content .projected-content'),
        );

        expect(projectedContent).toBeTruthy();
    });

    it('should render default projected action in the `.mp-edit-abstract-product__heading-col` element', () => {
        const projectedAction = fixture.debugElement.query(
            By.css('.mp-edit-abstract-product__heading-col .projected-action'),
        );

        expect(projectedAction).toBeTruthy();
    });
});
