import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { CreateConcreteProductsOverlayComponent } from './create-concrete-products-overlay.component';

@Component({
    selector: 'spy-test',
    template: `
        <mp-create-concrete-products-overlay [product]="product">
            <span title class="projected-title"></span>
            <span action class="projected-action"></span>
            <div class="projected-content"></div>
        </mp-create-concrete-products-overlay>
    `,
})
class TestComponent {
    product: any;
}

const mockProduct = {
    name: 'test name',
    sku: 'test sku',
};

describe('CreateConcreteProductsOverlayComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [CreateConcreteProductsOverlayComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render <spy-headline> component', () => {
        const headlineElem = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineElem).toBeTruthy();
    });

    it('should render default projected title to the `.mp-create-concrete-products-overlay__title` element', () => {
        const projectedTitle = fixture.debugElement.query(
            By.css('.mp-create-concrete-products-overlay__title .projected-title'),
        );

        expect(projectedTitle).toBeTruthy();
    });

    it('should render `product.name` and `product.sku` to the `.mp-create-concrete-products-overlay__sub-title` element', () => {
        const titleHolder = fixture.debugElement.query(By.css('.mp-create-concrete-products-overlay__sub-title'));

        component.product = mockProduct;
        fixture.detectChanges();

        expect(titleHolder.nativeElement.textContent).toContain(`${mockProduct.sku}, ${mockProduct.name}`);
    });

    it('should render default projected action to the <spy-headline> component', () => {
        const projectedAction = fixture.debugElement.query(By.css('spy-headline .projected-action'));

        expect(projectedAction).toBeTruthy();
    });

    it('should render default projected content to the `.mp-create-concrete-products-overlay__content` element', () => {
        const projectedContent = fixture.debugElement.query(
            By.css('.mp-create-concrete-products-overlay__content .projected-content'),
        );

        expect(projectedContent).toBeTruthy();
    });
});
