import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { TestLocaleModule } from '@spryker/locale/testing';
import { LayoutFooterComponent } from './layout-footer.component';
import { By } from '@angular/platform-browser';

describe('LayoutFooterComponent', () => {
    let component: LayoutFooterComponent;
    let fixture: ComponentFixture<LayoutFooterComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [TestLocaleModule],
            declarations: [LayoutFooterComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(LayoutFooterComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('is the current date', () => {
        fixture.detectChanges();
        const footerElem = fixture.debugElement.query(By.css('.footer span'));
        const currentYear = new Date().getFullYear();

        expect(footerElem.nativeElement.textContent).toMatch(currentYear.toString());
    });
});
