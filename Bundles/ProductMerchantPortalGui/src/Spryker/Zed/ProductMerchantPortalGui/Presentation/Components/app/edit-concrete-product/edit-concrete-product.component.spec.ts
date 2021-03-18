import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { EditConcreteProductComponent } from './edit-concrete-product.component';

@Component({
    selector: 'spy-test',
    template: `
        <mp-edit-concrete-product>
            <span title class="projected-title">Name</span>
            <span sub-title class="projected-sub-title">Sku</span>
            <span action class="projected-action">Button</span>
            <div class="projected-content">Content</div>
        </mp-edit-concrete-product>
    `,
})
class TestComponent {}

describe('EditConcreteProductComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [EditConcreteProductComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render default projected title to the `.mp-edit-concrete-product__heading-col` element', () => {
        const projectedTitle = fixture.debugElement.query(
            By.css('.mp-edit-concrete-product__heading-col .projected-title'),
        );

        expect(projectedTitle.nativeElement.textContent).toBe('Name');
    });

    it('should render default projected sub-title to the `.mp-edit-concrete-product__heading-col` element', () => {
        const projectedSubTitle = fixture.debugElement.query(
            By.css('.mp-edit-concrete-product__heading-col .projected-sub-title'),
        );

        expect(projectedSubTitle.nativeElement.textContent).toBe('Sku');
    });

    it('should render default projected action to the `.mp-edit-concrete-product__heading-col` element', () => {
        const projectedAction = fixture.debugElement.query(
            By.css('.mp-edit-concrete-product__heading-col .projected-action'),
        );

        expect(projectedAction.nativeElement.textContent).toBe('Button');
    });

    it('should render default projected content to the `.mp-edit-concrete-product__content` element', () => {
        const projectedContent = fixture.debugElement.query(
            By.css('.mp-edit-concrete-product__content .projected-content'),
        );

        expect(projectedContent.nativeElement.textContent).toBe('Content');
    });
});
