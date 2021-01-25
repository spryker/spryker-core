import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';

import { DashboardCardModule } from './dashboard-card.module';

describe('DashboardCardComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        template: `
            <mp-dashboard-card [count]="count">
                <div class="default-content"></div>
                <div title class="title-content"></div>
                <div actions class="actions-content"></div>
            </mp-dashboard-card>
        `,
    })
    class TestComponent {
        count?: string;
    }

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [DashboardCardModule],
            declarations: [TestComponent],
            schemas: [NO_ERRORS_SCHEMA],
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

    it('should render actions content in the `.ant-card-extra` element', () => {
        fixture.detectChanges();

        const actionsContentElement = fixture.debugElement.query(By.css('.ant-card-extra .actions-content'));

        expect(actionsContentElement).toBeTruthy();
    });

    it('should render spy-chips component in the `.ant-card-head-title` if @Input(count) has bound', () => {
        const mockCount = '5';

        component.count = mockCount;
        fixture.detectChanges();

        const chipsComponent = fixture.debugElement.query(By.css('.ant-card-head-title spy-chips'));

        expect(chipsComponent).toBeTruthy();
        expect(chipsComponent.nativeElement.textContent).toContain(mockCount);
    });
});
