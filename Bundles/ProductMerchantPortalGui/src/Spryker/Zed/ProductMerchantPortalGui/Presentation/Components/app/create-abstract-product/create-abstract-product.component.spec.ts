import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { CreateAbstractProductComponent } from './create-abstract-product.component';

@Component({
    selector: 'spy-test',
    template: `
        <mp-create-abstract-product>
            <span title class="projected-title">Name</span>
            <span action class="projected-action">Button</span>
            <div class="projected-content">Content</div>
        </mp-create-abstract-product>
    `,
})
class TestComponent {}

describe('CreateAbstractProductComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [CreateAbstractProductComponent, TestComponent],
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

    it('should render default projected title to the `.mp-create-abstract-product__header` element', () => {
        const projectedTitle = fixture.debugElement.query(
            By.css('.mp-create-abstract-product__header .projected-title'),
        );

        expect(projectedTitle.nativeElement.textContent).toBe('Name');
    });

    it('should render default projected action to the `.mp-create-abstract-product__header` element', () => {
        const projectedAction = fixture.debugElement.query(
            By.css('.mp-create-abstract-product__header .projected-action'),
        );

        expect(projectedAction.nativeElement.textContent).toBe('Button');
    });

    it('should render default projected content to the `.mp-create-abstract-product__content` element', () => {
        const projectedContent = fixture.debugElement.query(
            By.css('.mp-create-abstract-product__content .projected-content'),
        );

        expect(projectedContent.nativeElement.textContent).toBe('Content');
    });
});
