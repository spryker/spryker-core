import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { Component, NO_ERRORS_SCHEMA } from '@angular/core';

import { MerchantLayoutMainComponent } from './merchant-layout-main.component';
import { LayoutMainComponent } from '../layout-main/layout-main.component';

describe('MerchantLayoutMainComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test-component',
        template: `
            <mp-merchant-layout-main [navigationConfig]="navigationConfig">
                <div name="header">Header Slot</div>
                Main Slot
            </mp-merchant-layout-main>
        `,
    })
    class TestComponent {
        navigationConfig: any;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [TestComponent, MerchantLayoutMainComponent],
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

    it('should render <mp-layout-main>', () => {
        const centeredLayoutElem = fixture.debugElement.query(By.css('mp-layout-main'));

        expect(centeredLayoutElem).toBeTruthy();
    });

    it('should bound navigationConfig to `mp-layout-main`', () => {
        const demoData =
            '[{"title":"Dashboard","url":"\\/dashboard","icon":"fa fa-area-chart","isActive":false,"subItems":[]}]';
        const layoutMain = fixture.debugElement.query(By.css('mp-layout-main'));

        component.navigationConfig = demoData;
        fixture.detectChanges();

        expect(layoutMain.properties.navigationConfig).toBe(demoData);
    });
});
