import { Component } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { ImageSliderModule } from './image-slider.module';

describe('ImageSliderComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;
    const mockedImages = [
        {
            src: 'mockSrc1',
            alt: 'mockAlt1',
        },
        {
            src: 'mockSrc2',
            alt: 'mockAlt2',
        },
        {
            src: 'mockSrc3',
            alt: 'mockAlt3',
        },
    ];

    @Component({
        selector: 'mp-test',
        template: ` <mp-image-slider [images]="images"></mp-image-slider> `,
    })
    class TestComponent {
        images: any;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ImageSliderModule],
            declarations: [TestComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        component.images = mockedImages;
        fixture.detectChanges();

        expect(component).toBeTruthy();
    });

    it('should render div with image-slider-preview if number of images > 1', () => {
        component.images = mockedImages;
        fixture.detectChanges();

        const sliderPreview = fixture.debugElement.query(By.css('.image-slider-preview'));

        expect(sliderPreview).toBeTruthy();
    });

    it('should NOT render image slider if number of images is 0', () => {
        component.images = [];
        fixture.detectChanges();

        const sliderPreview = fixture.debugElement.query(By.css('.image-slider'));

        expect(sliderPreview).toBeFalsy();
    });

    it('should render only showcard if number of images is 1', () => {
        component.images = [mockedImages[0]];
        fixture.detectChanges();

        const sliderPreview = fixture.debugElement.query(By.css('.image-slider-preview'));

        expect(sliderPreview).toBeFalsy();
    });

    it('should render div with image-slider-preview with only 3 images if images array length > 3', () => {
        component.images = [...mockedImages, ...mockedImages];
        fixture.detectChanges();

        const sliderPreview = fixture.debugElement.query(By.css('.image-slider-preview'));
        const imagesArrayLength = sliderPreview.nativeElement.children.length;

        expect(imagesArrayLength === 3).toBeTruthy();
    });

    it('first image from the list should be shown by default', () => {
        component.images = mockedImages;
        fixture.detectChanges();

        const sliderShowcardImg = fixture.debugElement.query(By.css('.image-slider-showcard-image'));

        expect(sliderShowcardImg.properties.src).toBe(mockedImages[0].src);
        expect(sliderShowcardImg.properties.alt).toBe(mockedImages[0].alt);
    });

    it('mouseover event on image from preview should change showcard image src and alt', () => {
        component.images = mockedImages;
        fixture.detectChanges();

        const sliderPreview = fixture.debugElement.query(By.css('.image-slider-preview-img:nth-child(2)'));

        sliderPreview.triggerEventHandler('mouseover', {});

        fixture.detectChanges();

        const sliderShowcardImg = fixture.debugElement.query(By.css('.image-slider-showcard-image'));

        expect(sliderShowcardImg.properties.src).toBe(mockedImages[1].src);
        expect(sliderShowcardImg.properties.alt).toBe(mockedImages[1].alt);
    });
});
