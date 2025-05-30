import { waitForAsync, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { EditConcreteProductImageSetsComponent } from './edit-concrete-product-image-sets.component';

/* eslint-disable */
@Component({
    selector: 'spy-test',
    template: `
        <mp-edit-concrete-product-image-sets [images]="images">
            <div class="projected-content"></div>
        </mp-edit-concrete-product-image-sets>
    `,
})
class TestComponent {
    images?: any;
}

describe('EditConcreteProductImageSetsComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    beforeEach(waitForAsync(() => {
        TestBed.configureTestingModule({
            declarations: [EditConcreteProductImageSetsComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render `projected-content` inside component', () => {
        const projectedContent = fixture.debugElement.query(By.css('.projected-content'));

        expect(projectedContent).toBeTruthy();
    });

    it('should render list from `@Input(images)` input', () => {
        const images = [{ src: 'src' }, { src: 'src' }, { src: 'src' }];

        component.images = images;
        fixture.detectChanges();

        const imagesComponents = fixture.debugElement.queryAll(
            By.css('.mp-edit-concrete-product-image-sets__images img'),
        );

        expect(imagesComponents.length).toBe(images.length);
    });

    it('should render `src` input from `@Input(images)` for an appropriate image', () => {
        const images = [{ src: 'src' }];

        component.images = images;
        fixture.detectChanges();

        const imageComponent = fixture.debugElement.query(By.css('.mp-edit-concrete-product-image-sets__images img'));

        expect(imageComponent.properties.src).toBe(images[0].src);
    });

    it('should render `alt` input from `@Input(images)` for an appropriate image', () => {
        const images = [{ alt: 'alt' }];

        component.images = images;
        fixture.detectChanges();

        const imageComponent = fixture.debugElement.query(By.css('.mp-edit-concrete-product-image-sets__images img'));

        expect(imageComponent.properties.alt).toBe(images[0].alt);
    });
});
/* eslint-enable */
