import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AuthFooterComponent } from './auth-footer.component';
import { By } from '@angular/platform-browser';

describe('ZedAuthFooterComponent', () => {
    let component: AuthFooterComponent;
    let fixture: ComponentFixture<AuthFooterComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [AuthFooterComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(AuthFooterComponent);
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
