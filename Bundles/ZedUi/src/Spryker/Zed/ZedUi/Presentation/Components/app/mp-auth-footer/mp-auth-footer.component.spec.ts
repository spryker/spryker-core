import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MpAuthFooterComponent } from './mp-auth-footer.component';
import { By } from '@angular/platform-browser';

describe('ZedAuthFooterComponent', () => {
    let component: MpAuthFooterComponent;
    let fixture: ComponentFixture<MpAuthFooterComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [MpAuthFooterComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(MpAuthFooterComponent);
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
