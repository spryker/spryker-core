import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { EditAbstractProductComponent } from './edit-abstract-product.component';

@Component({
    selector: 'spy-test',
    template: `
        <mp-edit-abstract-product [product]="product">
            <span title class="projected-title">Title</span>
            <span action class="projected-action">Button</span>
            <div class="projected-content">Content</div>
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

    it('should render <spy-headline> component', () => {
        const headlineElem = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineElem).toBeTruthy();
    });

    it('should render default projected title to the `.mp-edit-abstract-product__title` element', () => {
        const projectedTitle = fixture.debugElement.query(By.css('.mp-edit-abstract-product__title .projected-title'));

        expect(projectedTitle).toBeTruthy();
        expect(projectedTitle.nativeElement.textContent).toBe('Title');
    });

    it('should render `product.name` and `product.sku` to the `.mp-edit-abstract-product__sub-title` element', () => {
        const titleHolder = fixture.debugElement.query(By.css('.mp-edit-abstract-product__sub-title'));

        component.product = mockProduct;
        fixture.detectChanges();

        expect(titleHolder.nativeElement.textContent).toContain(`${mockProduct.sku}, ${mockProduct.name}`);
    });

    it('should render default projected action to the <spy-headline> component', () => {
        const projectedAction = fixture.debugElement.query(By.css('spy-headline .projected-action'));

        expect(projectedAction).toBeTruthy();
        expect(projectedAction.nativeElement.textContent).toBe('Button');
    });

    it('should render default projected content to the `.mp-edit-abstract-product__content` element', () => {
        const projectedContent = fixture.debugElement.query(
            By.css('.mp-edit-abstract-product__content .projected-content'),
        );

        expect(projectedContent).toBeTruthy();
        expect(projectedContent.nativeElement.textContent).toBe('Content');
    });
});
