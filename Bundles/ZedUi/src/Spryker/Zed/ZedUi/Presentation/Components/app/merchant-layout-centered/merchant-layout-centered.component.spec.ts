import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { Component } from '@angular/core';
import { By } from '@angular/platform-browser';

import { MerchantLayoutCenteredModule } from './merchant-layout-centered.module';

describe('ZedMerchantLayoutCentralComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: ` <mp-merchant-layout-centered>Content</mp-merchant-layout-centered> `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [MerchantLayoutCenteredModule],
            declarations: [TestComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;

        fixture.detectChanges();
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('should render <mp-layout-centered>', () => {
        const centeredLayoutElem = fixture.debugElement.query(By.css('mp-layout-centered'));

        expect(centeredLayoutElem).toBeTruthy();
    });
});
