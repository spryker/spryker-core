import { async, TestBed, ComponentFixture } from '@angular/core/testing';
import { Component } from '@angular/core';
import { CardModule } from '@spryker/card';
import { LogoModule } from '@spryker/logo';
import { By } from '@angular/platform-browser';

import { LoginLayoutComponent } from './login-layout.component';

describe('LoginLayoutComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <mp-login-layout>
                <div class="default-content"></div>
                <div sub-title class="sub-title-content"></div>
                <div title class="title-content"></div>
            </mp-login-layout>
        `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [CardModule, LogoModule],
            declarations: [LoginLayoutComponent, TestComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('should render `spy-logo` component', () => {
        const logoElem = fixture.debugElement.query(By.css('spy-logo'));

        expect(logoElem).toBeTruthy();
    });

    it('should render sub-title content in the `.login__logo-text`', () => {
        fixture.detectChanges();

        const subTitleElem = fixture.debugElement.query(By.css('.login__logo-text .sub-title-content'));

        expect(subTitleElem).toBeTruthy();
    });

    it('should render `spy-card` component', () => {
        const cardElem = fixture.debugElement.query(By.css('spy-card'));

        expect(cardElem).toBeTruthy();
    });

    it('should render default content in the `.ant-card-body` element', () => {
        fixture.detectChanges();

        const defaultContentElement = fixture.debugElement.query(By.css('.ant-card-body .default-content'));

        expect(defaultContentElement).toBeTruthy();
    });

    it('should render title content in the `.ant-card-head-title` element', () => {
        fixture.detectChanges();

        const titleContentElement = fixture.debugElement.query(By.css('.ant-card-head-title .title-content'));

        expect(titleContentElement).toBeTruthy();
    });
});
