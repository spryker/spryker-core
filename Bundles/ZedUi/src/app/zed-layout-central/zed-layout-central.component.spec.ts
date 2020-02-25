import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { Component } from '@angular/core';
import { By } from '@angular/platform-browser';

import { ZedLayoutCentralComponent } from './zed-layout-central.component';
import { ZedAuthFooterModule } from '../zed-auth-footer/zed-auth-footer.module';

describe('ZedLayoutCentralComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <zed-layout-central>Content</zed-layout-central>
        `
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ZedAuthFooterModule],
            declarations: [ZedLayoutCentralComponent, TestComponent]
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

    it('is ng-content renderer inside `zed-layout-central` component', () => {
        const footerContentElem = fixture.debugElement.query(By.css('.zed-layout-central__content'));
        expect(footerContentElem.nativeElement.textContent).toMatch('Content');
    });
});
