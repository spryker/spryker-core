import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { Component } from '@angular/core';
import { By } from '@angular/platform-browser';

import { MpLayoutCenteredComponent } from './mp-layout-centered.component';
import { MpAuthFooterModule } from '../mp-auth-footer/mp-auth-footer.module';

describe('ZedLayoutCentralComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <mp-layout-centered>Content</mp-layout-centered>
        `
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [MpAuthFooterModule],
            declarations: [MpLayoutCenteredComponent, TestComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('component must render `mp-auth-footer` component', () => {
        const footerElem = fixture.debugElement.query(By.css('mp-auth-footer'));
        expect(footerElem).toBeTruthy();
    });

    it('is ng-content renderer inside `mp-layout-centered` component', () => {
        const footerContentElem = fixture.debugElement.query(By.css('.mp-layout-centered__content'));
        expect(footerContentElem.nativeElement.textContent).toMatch('Content');
    });
});
