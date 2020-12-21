import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { ImageSetsComponent } from './image-sets.component';
import { Component, NO_ERRORS_SCHEMA } from '@angular/core';

@Component({
  selector: 'spy-test-component',
  template: `
    <mp-image-sets [images]="images">
        <div class="projected-conntent"></div>
    </mp-image-sets>
  `,
})
class TestComponent {
  images?: any;
}

describe('ImageSetsComponent', () => {
  let component: TestComponent;
  let fixture: ComponentFixture<TestComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ImageSetsComponent, TestComponent],
      schemas: [NO_ERRORS_SCHEMA],
    }).compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TestComponent);
    component = fixture.componentInstance;
  });

  it('should render `projected-conntent` inside component', () => {
    const projectedContent = fixture.debugElement.query(By.css('.projected-conntent'));

    expect(projectedContent).toBeTruthy();
  });

  it('should render list from @Input(images)', () => {
    const images = [
      { src: 'src' },
      { src: 'src' },
      { src: 'src' },
    ];

    component.images = images;
    fixture.detectChanges();

    const imagesComponents = fixture.debugElement.queryAll(By.css('.mp-image-sets__images img'));

    expect(imagesComponents.length).toBe(images.length);
  });

  it('should render `src` input from @Input(images) for an appropriate image', () => {
    const images = [
      { src: 'src' },
    ];

    component.images = images;
    fixture.detectChanges();

    const imageComponent = fixture.debugElement.query(By.css('.mp-image-sets__images img'));

    expect(imageComponent.properties.src).toBe(images[0].src);
  });

  it('should render `alt` input from @Input(images) for an appropriate image', () => {
    const images = [
      { alt: 'alt' },
    ];

    component.images = images;
    fixture.detectChanges();

    const imageComponent = fixture.debugElement.query(By.css('.mp-image-sets__images img'));

    expect(imageComponent.properties.alt).toBe(images[0].alt);
  });
});
