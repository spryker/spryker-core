import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { Component } from '@angular/core';
import { By } from '@angular/platform-browser';

import { ZedLayoutCenteredComponent } from './zed-layout-centered.component';
import { ZedAuthFooterModule } from '../zed-auth-footer/zed-auth-footer.module';

describe('ZedLayoutCentralComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <zed-layout-centered>Content</zed-layout-centered>
        `
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ZedAuthFooterModule],
            declarations: [ZedLayoutCenteredComponent, TestComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('component must render `zed-auth-footer` component', () => {
        const footerElem = fixture.debugElement.query(By.css('zed-auth-footer'));
        expect(footerElem).toBeTruthy();
    });

    it('is ng-content renderer inside `zed-layout-centered` component', () => {
        const footerContentElem = fixture.debugElement.query(By.css('.zed-layout-centered__content'));
        expect(footerContentElem.nativeElement.textContent).toMatch('Content');
    });
});
