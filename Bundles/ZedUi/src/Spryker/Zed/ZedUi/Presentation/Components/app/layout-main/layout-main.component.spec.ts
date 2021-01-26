import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { Component, NO_ERRORS_SCHEMA } from '@angular/core';

import { LayoutMainComponent } from './layout-main.component';

describe('LayoutMainComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test-component',
        template: `
            <mp-layout-main [navigationConfig]="navigationConfig">
                <div name="header">Header Slot</div>
                Main Slot
            </mp-layout-main>
        `,
    })
    class TestComponent {
        navigationConfig: any;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [TestComponent, LayoutMainComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    describe('Elements detection', () => {
        it('should render <spy-sidebar> component', () => {
            const sidebarElem = fixture.debugElement.query(By.css('spy-sidebar'));

            expect(sidebarElem).toBeTruthy();
        });

        it('should render <spy-header> component', () => {
            const headerElem = fixture.debugElement.query(By.css('spy-header'));

            expect(headerElem).toBeTruthy();
        });

        it('should render <spy-logo> component', () => {
            const logoElem = fixture.debugElement.query(By.css('spy-logo'));

            expect(logoElem).toBeTruthy();
        });

        it('should render <spy-navigation> inside <spy-sidebar> component', () => {
            const navigationElem = fixture.debugElement.query(By.css('spy-sidebar spy-navigation'));

            expect(navigationElem).toBeTruthy();
        });
    });

    describe('isCollapsed property', () => {
        let layoutComponent: LayoutMainComponent;
        let layoutFixture: ComponentFixture<LayoutMainComponent>;

        beforeEach(() => {
            layoutFixture = TestBed.createComponent(LayoutMainComponent);
            layoutComponent = layoutFixture.componentInstance;
        });

        it('should bind to `collapsed` of <spy-navigation>', () => {
            const navigationElem = layoutFixture.debugElement.query(By.css('spy-sidebar spy-navigation'));

            layoutComponent.isCollapsed = true;

            layoutFixture.detectChanges();

            expect(navigationElem.properties.collapsed).toBe(true);
        });

        it('should change if `updateCollapseHandler` method invokes', () => {
            layoutComponent.updateCollapseHandler(true);

            layoutFixture.detectChanges();

            expect(layoutComponent.isCollapsed).toBe(true);
        });
    });

    describe('Slots', () => {
        it('should render correct info inside `header` slot', () => {
            const headerSlotContainerElem = fixture.debugElement.query(By.css('.mp-layout-main-cnt__header'));

            expect(headerSlotContainerElem.nativeElement.textContent).toMatch('Header Slot');
        });

        it('should render correct info inside main slot', () => {
            const mainSlotContainerElem = fixture.debugElement.query(By.css('.mp-layout-main-cnt__content'));

            expect(mainSlotContainerElem.nativeElement.textContent).toMatch('Main Slot');
        });
    });

    describe('@Input(navigationConfig)', () => {
        it('should bind to `items` of <spy-navigation>', () => {
            const demoData =
                '[{"title":"Dashboard","url":"\\/dashboard","icon":"fa fa-area-chart","isActive":false,"subItems":[]}]';
            const navigationElem = fixture.debugElement.query(By.css('spy-sidebar spy-navigation'));

            component.navigationConfig = demoData;

            fixture.detectChanges();

            expect(navigationElem.properties.items).toBe(demoData);
        });

        it('should update binding when changed', () => {
            const demoData =
                '[{"title":"Dashboard","url":"\\/dashboard","icon":"fa fa-area-chart","isActive":false,"subItems":[]}]';
            const navigationElem = fixture.debugElement.query(By.css('spy-sidebar spy-navigation'));

            component.navigationConfig = demoData;

            fixture.detectChanges();

            expect(navigationElem.properties.items).toBe(demoData);

            component.navigationConfig = '';

            fixture.detectChanges();

            expect(navigationElem.properties.items).toBe('');
        });
    });
});
