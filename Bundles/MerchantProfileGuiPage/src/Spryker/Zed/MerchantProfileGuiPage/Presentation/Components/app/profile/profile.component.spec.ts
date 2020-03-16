import { async, TestBed, ComponentFixture } from '@angular/core/testing';
import { Component } from '@angular/core';
import { By } from '@angular/platform-browser';

import { ProfileModule } from './profile.module';

describe('LoginComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <mp-profile>
                <h1 title>Title</h1>
                <spy-button action type="submit">
                    Button
                </spy-button>
            </mp-profile>
        `
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ProfileModule],
            declarations: [TestComponent]
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;
    });

    it('should create component', () => {
        expect(component).toBeTruthy();
    });

	describe('Header', () => {
		it('should render page header',  () => {
			const host = await createComponent({ animateSlides: true }, true);
			const tabsElement = host.queryCss('nz-tabset')!;

			expect(tabsElement.properties.nzAnimated).toBe(true);
		});
	});

	it('should create component', () => {
		expect(component).toBeTruthy();
	});
});
