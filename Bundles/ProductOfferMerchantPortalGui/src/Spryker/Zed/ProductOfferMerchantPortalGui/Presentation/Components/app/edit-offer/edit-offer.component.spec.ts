import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { EditOfferComponent } from './edit-offer.component';

const mockTitleContent = 'mockTitleContent';
const mockActionContent = 'mockActionContent';
const mockProductStatusContent = 'mockProductStatusContent';
const mockProductDetailsContent = 'mockProductDetailsContent';
const mockDefaultContent = 'mockDefaultContent';

describe('EditOfferComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test-edit-offer',
        template: `
            <mp-edit-offer [productDetailsTitle]="productDetailsTitle" [images]="images" [product]="product">
                <div title>${mockTitleContent}</div>
                <div action>${mockActionContent}</div>
                <div product-status>${mockProductStatusContent}</div>
                <div product-details>${mockProductDetailsContent}</div>
                <div>${mockDefaultContent}</div>
            </mp-edit-offer>
        `,
    })
    class TestComponent {
        productDetailsTitle: any;
        images: any;
        product: any;
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

    it('should render component with `mp-edit-offer` host class', () => {
        const editOfferElem = fixture.debugElement.query(By.css('.mp-edit-offer'));

        expect(editOfferElem).toBeTruthy();
    });

    it('should render slot [title] as the title of the drawer', () => {
        const titleHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__heading-col:first-child'));

        expect(titleHolderElem.nativeElement.textContent).toContain(mockTitleContent);
    });

    it('should render slot [action] in the last element with `mp-edit-offer__heading-col` className', () => {
        const actionHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__heading-col:last-child'));

        expect(actionHolderElem.nativeElement.textContent).toContain(mockActionContent);
    });

    it('should render slot [product-status] in the element with `mp-edit-offer__base-col--content` className', () => {
        const productStatusHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__base-col--content'));

        expect(productStatusHolderElem.nativeElement.textContent).toContain(mockProductStatusContent);
    });

    it('should render slot [product-details] in the `spy-collapsible` component', () => {
        const productDetailsHolderElem = fixture.debugElement.query(By.css('spy-collapsible'));

        expect(productDetailsHolderElem.nativeElement.textContent).toContain(mockProductDetailsContent);
    });

    it('should render default slot after `.mp-edit-offer__information` element', () => {
        const defaultSlotHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__information + div'));

        expect(defaultSlotHolderElem.nativeElement.textContent).toContain(mockDefaultContent);
    });

    it('should bind @Input(productDetailsTitle) to input `title` of `spy-collapsible` component', () => {
        const mockProductDetailsTitle = 'productDetailsTitle';
        const collapsibleElem = fixture.debugElement.query(By.css('spy-collapsible'));

        component.productDetailsTitle = mockProductDetailsTitle;
        fixture.detectChanges();

        expect(collapsibleElem.properties.spyTitle).toBe(mockProductDetailsTitle);
    });

    it('should bind @Input(images) to input `images` of `mp-image-slider` component', () => {
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

    it('should render @Input(product) data in the appropriate places', () => {
        const mockProduct = {
            name: 'name',
            sku: 'sku',
            validFrom: 'validFrom',
            validTo: 'validTo',
            validFromTitle: 'validFromTitle',
            validToTitle: 'validToTitle',
        };
        const nameHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__title'));
        const skuHolderElem = fixture.debugElement.query(By.css('.mp-edit-offer__sku'));
        const validFromHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__dates-col:first-child .mp-edit-offer__dates-value'),
        );
        const validToHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__dates-col:last-child .mp-edit-offer__dates-value'),
        );
        const validFromTitleHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__dates-col:first-child .mp-edit-offer__dates-title'),
        );
        const validToTitleHolderElem = fixture.debugElement.query(
            By.css('.mp-edit-offer__dates-col:last-child .mp-edit-offer__dates-title'),
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
