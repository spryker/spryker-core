import { Component } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { DashboardStatsModule } from './dashboard-stats.module';

describe('DashboardStatsComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        template: `
            <mp-dashboard-stats>
                <div class="default-content"></div>
                <div title class="title-content"></div>
            </mp-dashboard-stats>
    `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [DashboardStatsModule],
            declarations: [TestComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
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
