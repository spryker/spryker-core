import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { DashboardComponent } from './dashboard.component';

describe('DashboardStatsComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        template: `
            <mp-dashboard [title]="title">
                <div class="default-content"></div>
            </mp-dashboard>
        `,
    })
    class TestComponent {
        title?: string;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [TestComponent, DashboardComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should render default content after `spy-headline` component', () => {
        fixture.detectChanges();

        const defaultContentElement = fixture.debugElement.query(By.css('spy-headline + .default-content'));

        expect(defaultContentElement).toBeTruthy();
    });

    it('should render `spy-headline` component', () => {
        const headlineComponent = fixture.debugElement.query(By.css('spy-headline'));

        expect(headlineComponent).toBeTruthy();
    });
});
