import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { InvokeModule } from '@spryker/utils';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ImageSetsComponent } from './image-sets.component';

const mockedImageSets = [
    {
        name: 'Set first',
        images: [
            {
                idProductImage: 1,
                order: 2,
                srcLarge: 'src-large-value',
                srcSmall: 'src-small-value',
            },
            {
                idProductImage: 2,
                order: 1,
                srcLarge: 'src-large-second-value',
                srcSmall: 'src-small-second-value',
            },
        ],
    },
    {
        name: 'Set second',
        images: [
            {
                idProductImage: 3,
                order: 1,
                srcLarge: 'second-set-src-large-value',
                srcSmall: 'second-set-src-small-value',
            },
        ],
    },
];
const mockedImageSetNames = {
    prop: 'prop-value',
    name: 'name-value',
    images: 'images-value',
    order: 'order-value',
    urlSmall: 'urlSmall-value',
    urlLarge: 'urlLarge-value',
    idProductImageSet: 'idProductImageSet-value',
    idProductImage: 'idProductImage-value',
    originalIndex: 'originalIndex-value',
};
const titles = {
    addImageSet: 'Add Image Set',
    setName: 'Set name',
    deleteImageSet: 'Delete Image Set',
    imageOrder: 'Image Ordered',
    smallImageUrl: 'Small Image URL',
    largeImageUrl: 'Large Image URL',
    addImage: 'Add Image',
};
const mockedImageSetError = [
    {
        name: 'Name error',
        images: [
            {
                order: 'Order error',
                srcLarge: 'Src Large error',
                srcSmall: 'Src Small error',
            },
        ],
    },
];

describe('ImageSetsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ImageSetsComponent, {
        ngModule: {
            imports: [InvokeModule],
            schemas: [NO_ERRORS_SCHEMA],
        },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it(`should render <spy-button> component with '${titles.addImageSet}' content`, async () => {
        const host = await createComponentWrapper(createComponent, { titles: titles });
        const buttonElem = host.queryCss('spy-button.mp-image-sets__button--add-set');

        expect(buttonElem.nativeElement.textContent).toMatch(titles.addImageSet);
    });

    it(`should render ${mockedImageSets.length} 'Image sets'`, async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [...mockedImageSets],
            names: mockedImageSetNames,
            titles: titles,
        });
        const imageSetElems = host.fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));

        expect(imageSetElems.length).toBe(mockedImageSets.length);
    });

    describe('Should render <spy-form-item> component with <spy-input> component for `Image Set name`', () => {
        it(`should <spy-form-item> component has '${titles.setName}' content`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const formItemElem = host.queryCss('spy-form-item.mp-image-sets__set-name');

            expect(formItemElem.nativeElement.textContent).toMatch(titles.setName);
        });

        it(`should <spy-input> component has '${mockedImageSets[0].name}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.name}]' name`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const inputComponent = host.queryCss('spy-form-item.mp-image-sets__set-name spy-input');

            expect(inputComponent.properties.value).toBe(mockedImageSets[0].name);
            expect(inputComponent.properties.name).toBe(`${mockedImageSetNames.prop}[0][${mockedImageSetNames.name}]`);
        });
    });

    it(`should render <spy-button> component with '${titles.deleteImageSet}' content`, async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [...mockedImageSets],
            names: mockedImageSetNames,
            titles: titles,
        });
        const buttonElem = host.queryCss('spy-button.mp-image-sets__button--remove-set');

        expect(buttonElem.nativeElement.textContent).toMatch(titles.deleteImageSet);
    });

    it(`should render 'Image Set' with ${mockedImageSets[0].images.length} images`, async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [...mockedImageSets],
            names: mockedImageSetNames,
            titles: titles,
        });
        const imageSetElem = host.queryCss('.mp-image-sets__set');
        const imageSetElems = imageSetElem.queryAll(By.css('.mp-image-sets__images'));

        expect(imageSetElems.length).toBe(mockedImageSets[0].images.length);
    });

    describe('Should render <spy-form-item> component with <spy-input> component for `Image Set image order`', () => {
        it(`should <spy-form-item> component has '${titles.imageOrder}' content`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const formItemElem = host.queryCss('spy-form-item.mp-image-sets__order');

            expect(formItemElem.nativeElement.textContent).toMatch(titles.imageOrder);
        });

        it(`should <spy-input> component has '${mockedImageSets[0].images[0].order}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.order}]' name`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const inputComponent = host.queryCss('spy-form-item.mp-image-sets__order spy-input');

            expect(inputComponent.properties.value).toBe(mockedImageSets[0].images[0].order);
            expect(inputComponent.properties.name).toBe(
                `${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.order}]`,
            );
        });
    });

    it('should render <spy-button-icon> component if `imageSets.images.length` > 1', async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [...mockedImageSets],
            names: mockedImageSetNames,
            titles: titles,
        });
        const buttonClassName = 'spy-button-icon.mp-image-sets__button--remove-images';
        const imageSetElems = host.fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));
        const firstButtonElem = imageSetElems[0].query(By.css(buttonClassName));
        const secondButtonElem = imageSetElems[1].query(By.css(buttonClassName));

        expect(firstButtonElem).toBeTruthy();
        expect(secondButtonElem).toBeFalsy();
    });

    it(`should render <img> elements with '${mockedImageSets[0].images[0].srcSmall}' and '${mockedImageSets[0].images[0].srcLarge}' src`, async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [...mockedImageSets],
            names: mockedImageSetNames,
            titles: titles,
        });
        const imageSmallElem = host.queryCss('img.mp-image-sets__image--small');
        const imageLargeElem = host.queryCss('img.mp-image-sets__image--large');

        expect(imageSmallElem.properties.src).toBe(mockedImageSets[0].images[0].srcSmall);
        expect(imageLargeElem.properties.src).toBe(mockedImageSets[0].images[0].srcLarge);
    });

    it('should NOT render <img> elements if `imageSets.images.srcSmall` or `imageSets.images.srcLarge` are empty', async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [
                {
                    name: 'test',
                    images: [
                        {
                            order: 1,
                            srcLarge: '',
                            srcSmall: '',
                        },
                    ],
                },
            ],
            names: mockedImageSetNames,
            titles: titles,
        });

        const imageSmallElem = host.queryCss('img.mp-image-sets__image--small');
        const imageLargeElem = host.queryCss('img.mp-image-sets__image--large');

        expect(imageSmallElem).toBeFalsy();
        expect(imageLargeElem).toBeFalsy();
    });

    describe('Should render <spy-form-item> component with <spy-input> component for `Image Set image URL small`', () => {
        it(`should <spy-form-item> component has '${titles.smallImageUrl}' content`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const formItemElem = host.queryCss('spy-form-item.mp-image-sets__image-url-small');

            expect(formItemElem.nativeElement.textContent).toMatch(titles.smallImageUrl);
        });

        it(`should <spy-input> component has '${mockedImageSets[0].images[0].srcSmall}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlSmall}]' name`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const inputComponent = host.queryCss('spy-form-item.mp-image-sets__image-url-small spy-input');

            expect(inputComponent.properties.value).toBe(mockedImageSets[0].images[0].srcSmall);
            expect(inputComponent.properties.name).toBe(
                `${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlSmall}]`,
            );
        });
    });

    describe('Should render <spy-form-item> component with <spy-input> component for `Image Set image URL large`', () => {
        it(`should <spy-form-item> component has '${titles.largeImageUrl}' content`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const formItemElem = host.queryCss('spy-form-item.mp-image-sets__image-url-large');

            expect(formItemElem.nativeElement.textContent).toMatch(titles.largeImageUrl);
        });

        it(`should <spy-input> component has '${mockedImageSets[0].images[0].srcLarge}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlLarge}]' name`, async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const inputComponent = host.queryCss('spy-form-item.mp-image-sets__image-url-large spy-input');

            expect(inputComponent.properties.value).toBe(mockedImageSets[0].images[0].srcLarge);
            expect(inputComponent.properties.name).toBe(
                `${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlLarge}]`,
            );
        });
    });

    it(`should render <spy-button> component with '${titles.addImage}' content`, async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [...mockedImageSets],
            names: mockedImageSetNames,
            titles: titles,
        });
        const buttonElem = host.queryCss('spy-button.mp-image-sets__button--add-images');

        expect(buttonElem.nativeElement.textContent).toMatch(titles.addImage);
    });

    it('should bound `@Input(errors)` to the `error` input of <spy-form-item> components', async () => {
        const host = await createComponentWrapper(createComponent, {
            imageSets: [...mockedImageSets],
            names: mockedImageSetNames,
            titles: titles,
            errors: mockedImageSetError,
        });
        const nameFormItemElem = host.queryCss('spy-form-item.mp-image-sets__set-name');
        const imageOrderFormItemElem = host.queryCss('spy-form-item.mp-image-sets__order');
        const imageSrcSmallFormItemElem = host.queryCss('spy-form-item.mp-image-sets__image-url-small');
        const imageSrcLargeFormItemElem = host.queryCss('spy-form-item.mp-image-sets__image-url-large');

        expect(nameFormItemElem.properties.error).toMatch(mockedImageSetError[0].name);
        expect(imageOrderFormItemElem.properties.error).toMatch(mockedImageSetError[0].images[0].order);
        expect(imageSrcSmallFormItemElem.properties.error).toMatch(mockedImageSetError[0].images[0].srcSmall);
        expect(imageSrcLargeFormItemElem.properties.error).toMatch(mockedImageSetError[0].images[0].srcLarge);
    });

    describe('Buttons clicking', () => {
        it('should add `Image set`', async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
                errors: mockedImageSetError,
            });
            const buttonElem = host.queryCss('spy-button.mp-image-sets__button--add-set');

            buttonElem.triggerEventHandler('click', null);
            host.detectChanges();

            const imageSetElems = host.fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));
            const nameFormItemElems = host.fixture.debugElement.queryAll(
                By.css('spy-form-item.mp-image-sets__set-name'),
            );

            expect(imageSetElems.length).toBe(mockedImageSets.length + 1);
            expect(nameFormItemElems[0].properties.error).toBeFalsy();
            expect(nameFormItemElems[1].properties.error).toMatch(mockedImageSetError[0].name);
        });

        it('should remove `Image set`', async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
                errors: mockedImageSetError,
            });
            const nameFormItemElem = host.queryCss('spy-form-item.mp-image-sets__set-name');
            const buttonElem = host.queryCss('spy-button.mp-image-sets__button--remove-set');

            buttonElem.triggerEventHandler('click', null);
            host.detectChanges();

            const imageSetElems = host.fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));

            expect(imageSetElems.length).toBe(mockedImageSets.length - 1);
            expect(nameFormItemElem.properties.error).toBeFalsy();
        });

        it('should remove `Image set images`', async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
                errors: mockedImageSetError,
            });
            const buttonIconElem = host.queryCss('spy-button-icon.mp-image-sets__button--remove-images');

            buttonIconElem.triggerEventHandler('click', null);
            host.detectChanges();

            const imageSetElem = host.queryCss('.mp-image-sets__set');
            const imageSetElems = imageSetElem.queryAll(By.css('.mp-image-sets__images'));
            const imageOrderFormItemElem = imageSetElem.query(By.css('spy-form-item.mp-image-sets__order'));

            expect(imageSetElems.length).toBe(mockedImageSets[0].images.length - 1);
            expect(imageOrderFormItemElem.properties.error).toBeFalsy();
        });

        it('should add `Image set images`', async () => {
            const host = await createComponentWrapper(createComponent, {
                imageSets: [...mockedImageSets],
                names: mockedImageSetNames,
                titles: titles,
            });
            const buttonElem = host.queryCss('spy-button.mp-image-sets__button--add-images');

            buttonElem.triggerEventHandler('click', null);
            host.detectChanges();

            const imageSetElem = host.queryCss('.mp-image-sets__set');
            const imageSetElems = imageSetElem.queryAll(By.css('.mp-image-sets__images'));

            expect(imageSetElems.length).toBe(mockedImageSets[0].images.length + 1);
        });
    });
});
