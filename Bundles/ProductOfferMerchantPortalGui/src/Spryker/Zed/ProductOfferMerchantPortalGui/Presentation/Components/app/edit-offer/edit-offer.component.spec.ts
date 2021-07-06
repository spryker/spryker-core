import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { EditOfferComponent } from './edit-offer.component';

describe('EditOfferComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test-edit-offer',
        template: `
            <mp-edit-offer
                [product]="product"
                [images]="images"
                [productDetailsTitle]="productDetailsTitle"
                [productCardTitle]="productCardTitle"
            >
                <span title></span>
                <span sub-title></span>
                <span approval-status></span>
                <span action></span>
                <span product-status></span>
                <span product-details></span>
                <div class="projected-content"></div>
            </mp-edit-offer>
        `,
    })
    class TestComponent {
        product: any;
        images: any;
        productDetailsTitle: any;
        productCardTitle: any;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [EditOfferComponent, TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render <mp-edit-offer> component', () => {
        const editOfferElem = fixture.debugElement.query(By.css('mp-edit-offer'));

        expect(editOfferElem).toBeTruthy();
    });

    it('should render default slot to the <mp-edit-offer> component', () => {
        const defaultSlot = fixture.debugElement.query(By.css('mp-edit-offer .projected-content'));

        expect(defaultSlot).toBeTruthy();
    });

    it('should render <spy-headline> component', () => {
        const headlineElem = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineElem).toBeTruthy();
    });

    it('should render `[title]` slot to the <spy-headline> component', () => {
        const titleSlot = fixture.debugElement.query(By.css('spy-headline [title]'));

        expect(titleSlot).toBeTruthy();
    });

    it('should render `[sub-title]` slot to the <spy-headline> component', () => {
        const subTitleSlot = fixture.debugElement.query(By.css('spy-headline [sub-title]'));

        expect(subTitleSlot).toBeTruthy();
    });

    it('should render `[approval-status]` slot to the <spy-headline> component', () => {
        const approvalStatusSlot = fixture.debugElement.query(By.css('spy-headline [approval-status]'));

        expect(approvalStatusSlot).toBeTruthy();
    });

    it('should render `[action]` slot to the <spy-headline> component', () => {
        const actionSlot = fixture.debugElement.query(By.css('spy-headline [action]'));

        expect(actionSlot).toBeTruthy();
    });

    it('should render <spy-card> component', () => {
        const cardElem = fixture.debugElement.query(By.css('spy-card'));

        expect(cardElem).toBeTruthy();
    });

    it('should render <mp-image-slider> component to the <spy-card> component', () => {
        const mockImages = [
            {
                src: 'mockImages',
                alt: 'mockImages',
            },
        ];

        component.images = mockImages;
        fixture.detectChanges();

        const imageSliderElem = fixture.debugElement.query(By.css('spy-card mp-image-slider'));

        expect(imageSliderElem).toBeTruthy();
    });

    it('should render <spy-collapsible> component to the <spy-card> component', () => {
        const collapsibleElem = fixture.debugElement.query(By.css('spy-card spy-collapsible'));

        expect(collapsibleElem).toBeTruthy();
    });

    it('should render `[product-status]` slot to the <spy-card> component', () => {
        const productStatusSlot = fixture.debugElement.query(By.css('spy-card [product-status]'));

        expect(productStatusSlot).toBeTruthy();
    });

    it('should render `[product-details]` slot to the <spy-collapsible> component', () => {
        const productDetailsSlot = fixture.debugElement.query(By.css('spy-collapsible [product-details]'));

        expect(productDetailsSlot).toBeTruthy();
    });

    it('should bind `@Input(productDetailsTitle)` to `title` input of <spy-collapsible> component', () => {
        const mockProductDetailsTitle = 'productDetailsTitle';
        const collapsibleElem = fixture.debugElement.query(By.css('spy-collapsible'));

        component.productDetailsTitle = mockProductDetailsTitle;
        fixture.detectChanges();

        expect(collapsibleElem.properties.spyTitle).toBe(mockProductDetailsTitle);
    });

    it('should bind `@Input(images)` to `images` input of <mp-image-slider> component', () => {
        const mockImages = [
            {
                src: 'mockImages',
                alt: 'mockImages',
            },
        ];

        component.images = mockImages;
        fixture.detectChanges();

        const imageSliderElem = fixture.debugElement.query(By.css('mp-image-slider'));

        expect(imageSliderElem.properties.images).toEqual(mockImages);
    });

    it('should render `@Input(product)` data to the appropriate places', () => {
        const mockProduct = {
            name: 'name',
            sku: 'sku',
            validFrom: 'validFrom',
            validTo: 'validTo',
            validFromTitle: 'validFromTitle',
            validToTitle: 'validToTitle',
        };
        const nameHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__product-title'));
        const skuHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__product-sku'));
        const validFromHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__product-dates-col:first-child .mp-edit-offer__product-dates-value'),
        );
        const validToHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__product-dates-col:last-child .mp-edit-offer__product-dates-value'),
        );
        const validFromTitleHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__product-dates-col:first-child .mp-edit-offer__product-dates-title'),
        );
        const validToTitleHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__product-dates-col:last-child .mp-edit-offer__product-dates-title'),
        );

        component.product = mockProduct;
        fixture.detectChanges();

        expect(nameHolderElem.nativeElement.textContent).toContain(mockProduct.name);
        expect(skuHolderElem.nativeElement.textContent).toContain(mockProduct.sku);
        expect(validFromHolderElem.nativeElement.textContent).toContain(mockProduct.validFrom);
        expect(validToHolderElem.nativeElement.textContent).toContain(mockProduct.validTo);
        expect(validFromTitleHolderElem.nativeElement.textContent).toContain(mockProduct.validFromTitle);
        expect(validToTitleHolderElem.nativeElement.textContent).toContain(mockProduct.validToTitle);
    });
});
