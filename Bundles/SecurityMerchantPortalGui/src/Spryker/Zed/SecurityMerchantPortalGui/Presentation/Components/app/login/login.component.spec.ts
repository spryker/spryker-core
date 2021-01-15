import { async, TestBed, ComponentFixture } from '@angular/core/testing';
import { Component } from '@angular/core';
import { CardModule } from '@spryker/card';
import { LogoModule } from '@spryker/logo';
import { By } from '@angular/platform-browser';

import { LoginComponent } from './login.component';

describe('LoginComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: ` <mp-login>Card Content</mp-login> `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [CardModule, LogoModule],
            declarations: [LoginComponent, TestComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('component must render `spy-logo` component', () => {
        const logoElem = fixture.debugElement.query(By.css('spy-logo'));
        expect(logoElem).toBeTruthy();
    });

    it('component must render `spy-card` component', () => {
        const cardElem = fixture.debugElement.query(By.css('spy-card'));
        expect(cardElem).toBeTruthy();
    });

    it('is ng-content renderer inside `spy-card` component', () => {
        fixture.detectChanges();
        const cardElem = fixture.debugElement.query(By.css('spy-card'));
        expect(cardElem.nativeElement.textContent).toMatch('Card Content');
    });
});
