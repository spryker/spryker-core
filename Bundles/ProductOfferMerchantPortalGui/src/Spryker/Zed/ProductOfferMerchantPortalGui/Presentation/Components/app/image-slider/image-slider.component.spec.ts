import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ImageSliderComponent } from './image-slider.component';

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

describe('ImageSliderComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ImageSliderComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render `.image-slider-preview` element if number of images > 1', async () => {
        const host = await createComponentWrapper(createComponent, { images: mockedImages });
        const sliderPreviewElem = host.queryCss('.image-slider-preview');

        expect(sliderPreviewElem).toBeTruthy();
    });

    it('should NOT render image slider if number of images is 0', async () => {
        const host = await createComponentWrapper(createComponent, { images: [] });
        const sliderElem = host.queryCss('.image-slider');

        expect(sliderElem).toBeFalsy();
    });

    it('should render only showcard if number of images is 1', async () => {
        const host = await createComponentWrapper(createComponent, { images: [mockedImages[0]] });
        const sliderPreviewElem = host.queryCss('.image-slider-preview');

        expect(sliderPreviewElem).toBeFalsy();
    });

    it('should render `.image-slider-preview` element with only 3 images if images array length > 3', async () => {
        const host = await createComponentWrapper(createComponent, { images: [...mockedImages, ...mockedImages] });
        const sliderPreviewElem = host.queryCss('.image-slider-preview');
        const imagesArrayLength = sliderPreviewElem.nativeElement.children.length;

        expect(imagesArrayLength === 3).toBeTruthy();
    });

    it('should show first image from the list by default', async () => {
        const host = await createComponentWrapper(createComponent, { images: mockedImages });
        const sliderShowcardImgElem = host.queryCss('.image-slider-showcard-image');

        expect(sliderShowcardImgElem.properties.src).toBe(mockedImages[0].src);
        expect(sliderShowcardImgElem.properties.alt).toBe(mockedImages[0].alt);
    });

    it('should change showcard image src and alt by mouseover event on image from preview', async () => {
        const host = await createComponentWrapper(createComponent, { images: mockedImages });
        const sliderPreviewElem = host.queryCss('.image-slider-preview-img:nth-child(2)');

        sliderPreviewElem.triggerEventHandler('mouseover', null);
        host.detectChanges();

        const sliderShowcardImgElem = host.queryCss('.image-slider-showcard-image');

        expect(sliderShowcardImgElem.properties.src).toBe(mockedImages[1].src);
        expect(sliderShowcardImgElem.properties.alt).toBe(mockedImages[1].alt);
    });
});
