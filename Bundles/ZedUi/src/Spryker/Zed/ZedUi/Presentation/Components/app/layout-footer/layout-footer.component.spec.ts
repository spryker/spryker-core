import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { Component } from '@angular/core';
import { By } from '@angular/platform-browser';

import { LayoutFooterComponent } from './layout-footer.component';
import { LayoutCenteredComponent } from '../layout-centered/layout-centered.component';

describe('LayoutFooterComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <mp-layout-footer>
                <div class="default-content"></div>
            </mp-layout-footer>
        `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [],
            declarations: [LayoutFooterComponent, TestComponent],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

    it('should render default content in the `.footer` element', () => {
        const defaultContentElement = fixture.debugElement.query(By.css('.footer .default-content'));

        expect(defaultContentElement).toBeTruthy();
    });
});
