import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { ImageSetsComponent } from './image-sets.component';
import { ImageSets, ImageSetNames, ImageSetTitles } from './types';

describe('ImageSetsComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;
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

    @Component({
        selector: 'test',
        template: `<mp-image-sets [imageSets]="imageSets" [names]="names" [titles]="titles"></mp-image-sets>`,
    })
    class TestComponent {
        imageSets?: ImageSets[];
        names?: ImageSetNames;
        titles?: ImageSetTitles;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ImageSetsComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
        component.imageSets = [...mockedImageSets];
        component.names = mockedImageSetNames;
        component.titles = titles;
        fixture.detectChanges();
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it(`should render '<spy-button>' component with '${titles.addImageSet}' content`, () => {
        const buttonElem = fixture.debugElement.query(By.css('spy-button.mp-image-sets__button--add-set'));

        expect(buttonElem.nativeElement.textContent).toMatch(titles.addImageSet);
    });

    it(`should render ${mockedImageSets.length} 'Image sets'`, () => {
        const imageSetElements = fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));

        expect(imageSetElements.length).toBe(mockedImageSets.length);
    });

    describe(`should render '<spy-form-item>' component with '<spy-input>' component for 'Image Set name'`, () => {
        it(`should '<spy-form-item>' has '${titles.setName}' content`, () => {
            const formItemElem = fixture.debugElement.query(By.css('spy-form-item.mp-image-sets__set-name'));

            expect(formItemElem.nativeElement.textContent).toMatch(titles.setName);
        });

        it(`should '<spy-input>' has '${mockedImageSets[0].name}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.name}]' name`, () => {
            const inputElem = fixture.debugElement.query(By.css('spy-form-item.mp-image-sets__set-name spy-input'));

            expect(inputElem.properties.value).toBe(mockedImageSets[0].name);
            expect(inputElem.properties.name).toBe(`${mockedImageSetNames.prop}[0][${mockedImageSetNames.name}]`);
        });
    });

    it(`should render '<spy-button>' component with '${titles.deleteImageSet}' content`, () => {
        const buttonElem = fixture.debugElement.query(By.css('spy-button.mp-image-sets__button--remove-set'));

        expect(buttonElem.nativeElement.textContent).toMatch(titles.deleteImageSet);
    });

    it(`should render 'Image Set' with ${mockedImageSets[0].images.length} 'images'`, () => {
        const imageSetElem = fixture.debugElement.query(By.css('.mp-image-sets__set'));
        const imageSetElems = imageSetElem.queryAll(By.css('.mp-image-sets__images'));

        expect(imageSetElems.length).toBe(mockedImageSets[0].images.length);
    });

    describe(`should render '<spy-form-item>' component with '<spy-input>' component for 'Image Set image order'`, () => {
        it(`should '<spy-form-item>' has '${titles.imageOrder}' content`, () => {
            const formItemElem = fixture.debugElement.query(By.css('spy-form-item.mp-image-sets__order'));

            expect(formItemElem.nativeElement.textContent).toMatch(titles.imageOrder);
        });

        it(`should '<spy-input>' has '${mockedImageSets[0].images[0].order}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.order}]' name`, () => {
            const inputElem = fixture.debugElement.query(By.css('spy-form-item.mp-image-sets__order spy-input'));

            expect(inputElem.properties.value).toBe(mockedImageSets[0].images[0].order);
            expect(inputElem.properties.name).toBe(
                `${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.order}]`,
            );
        });
    });

    it(`should render '<spy-button-icon>' component if 'imageSets.images.length > 1'`, () => {
        const buttonClassName = 'spy-button-icon.mp-image-sets__button--remove-images';
        const imageSetElems = fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));
        const firstButtonElem = imageSetElems[0].query(By.css(buttonClassName));
        const secondButtonElem = imageSetElems[1].query(By.css(buttonClassName));

        expect(firstButtonElem).toBeTruthy();
        expect(secondButtonElem).toBeFalsy();
    });

    it(`should render '<img>' elements with '${mockedImageSets[0].images[0].srcSmall}' and '${mockedImageSets[0].images[0].srcLarge}' src`, () => {
        const imageSmallElem = fixture.debugElement.query(By.css('img.mp-image-sets__image--small'));
        const imageLargeElem = fixture.debugElement.query(By.css('img.mp-image-sets__image--large'));

        expect(imageSmallElem.properties.src).toBe(mockedImageSets[0].images[0].srcSmall);
        expect(imageLargeElem.properties.src).toBe(mockedImageSets[0].images[0].srcLarge);
    });

    it(`should NOT render '<img>' elements if 'imageSets.images.srcSmall' or 'imageSets.images.srcLarge' are empty`, () => {
        component.imageSets = [
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
        ];
        fixture.detectChanges();

        const imageSmallElem = fixture.debugElement.query(By.css('img.mp-image-sets__image--small'));
        const imageLargeElem = fixture.debugElement.query(By.css('img.mp-image-sets__image--large'));

        expect(imageSmallElem).toBeFalsy();
        expect(imageLargeElem).toBeFalsy();
    });

    describe(`should render '<spy-form-item>' component with '<spy-input>' component for 'Image Set image URL small'`, () => {
        it(`should '<spy-form-item>' has '${titles.smallImageUrl}' content`, () => {
            const formItemElem = fixture.debugElement.query(By.css('spy-form-item.mp-image-sets__image-url-small'));

            expect(formItemElem.nativeElement.textContent).toMatch(titles.smallImageUrl);
        });

        it(`should '<spy-input>' has '${mockedImageSets[0].images[0].srcSmall}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlSmall}]' name`, () => {
            const inputElem = fixture.debugElement.query(
                By.css('spy-form-item.mp-image-sets__image-url-small spy-input'),
            );

            expect(inputElem.properties.value).toBe(mockedImageSets[0].images[0].srcSmall);
            expect(inputElem.properties.name).toBe(
                `${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlSmall}]`,
            );
        });
    });

    describe(`should render '<spy-form-item>' component with '<spy-input>' component for 'Image Set image URL large'`, () => {
        it(`should '<spy-form-item>' has '${titles.largeImageUrl}' content`, () => {
            const formItemElem = fixture.debugElement.query(By.css('spy-form-item.mp-image-sets__image-url-large'));

            expect(formItemElem.nativeElement.textContent).toMatch(titles.largeImageUrl);
        });

        it(`should '<spy-input>' has '${mockedImageSets[0].images[0].srcLarge}' value and '${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlLarge}]' name`, () => {
            const inputElem = fixture.debugElement.query(
                By.css('spy-form-item.mp-image-sets__image-url-large spy-input'),
            );

            expect(inputElem.properties.value).toBe(mockedImageSets[0].images[0].srcLarge);
            expect(inputElem.properties.name).toBe(
                `${mockedImageSetNames.prop}[0][${mockedImageSetNames.images}][0][${mockedImageSetNames.urlLarge}]`,
            );
        });
    });

    it(`should render '<spy-button>' component with '${titles.addImage}' content`, () => {
        const buttonElem = fixture.debugElement.query(By.css('spy-button.mp-image-sets__button--add-images'));

        expect(buttonElem.nativeElement.textContent).toMatch(titles.addImage);
    });

    describe(`Buttons clicking`, () => {
        it(`should add 'Image set'`, () => {
            const buttonElem = fixture.debugElement.query(By.css('spy-button.mp-image-sets__button--add-set'));
            buttonElem.triggerEventHandler('click', {});
            fixture.detectChanges();
            const imageSetElements = fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));

            expect(imageSetElements.length).toBe(mockedImageSets.length + 1);
        });

        it(`should remove 'Image set'`, () => {
            const buttonElem = fixture.debugElement.query(By.css('spy-button.mp-image-sets__button--remove-set'));
            buttonElem.triggerEventHandler('click', {});
            fixture.detectChanges();
            const imageSetElements = fixture.debugElement.queryAll(By.css('.mp-image-sets__set'));

            expect(imageSetElements.length).toBe(mockedImageSets.length - 1);
        });

        it(`should remove 'Image set images'`, () => {
            const buttonElem = fixture.debugElement.query(
                By.css('spy-button-icon.mp-image-sets__button--remove-images'),
            );
            buttonElem.triggerEventHandler('click', {});
            fixture.detectChanges();
            const imageSetElem = fixture.debugElement.query(By.css('.mp-image-sets__set'));
            const imageSetElems = imageSetElem.queryAll(By.css('.mp-image-sets__images'));

            expect(imageSetElems.length).toBe(mockedImageSets[0].images.length - 1);
        });

        it(`should add 'Image set images'`, () => {
            const buttonElem = fixture.debugElement.query(By.css('spy-button.mp-image-sets__button--add-images'));
            buttonElem.triggerEventHandler('click', {});
            fixture.detectChanges();
            const imageSetElem = fixture.debugElement.query(By.css('.mp-image-sets__set'));
            const imageSetElems = imageSetElem.queryAll(By.css('.mp-image-sets__images'));

            expect(imageSetElems.length).toBe(mockedImageSets[0].images.length + 1);
        });
    });
});
