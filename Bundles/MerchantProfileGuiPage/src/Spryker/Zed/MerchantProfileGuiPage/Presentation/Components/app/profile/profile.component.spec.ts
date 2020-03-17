import { async, TestBed, ComponentFixture } from '@angular/core/testing';
import { Component } from '@angular/core';
import { By } from '@angular/platform-browser';

import { ProfileModule } from './profile.module';

describe('ProfileComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <mp-profile>
                <h1 title ngClass="test-title">Title</h1>
	            
	            Page Content
	            
                <spy-button action type="submit" ngClass="test-action">
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

        fixture.detectChanges();
    });

	it('should create component', () => {
        expect(component).toBeTruthy();
	});

	describe('Profile header', () => {
		it('should render page header',  () => {
			const headerElem = fixture.debugElement.query(By.css('.mp-profile__header'));

			expect(headerElem).toBeTruthy();
		});

		it('should render projected title inside header',  () => {
			const titleElem = fixture.debugElement.query(By.css('.mp-profile__header .test-title'));

			expect(titleElem).toBeTruthy();
		});

		it('should render projected action inside header',  () => {
			const actionElem = fixture.debugElement.query(By.css('.mp-profile__header .test-action'));

			expect(actionElem).toBeTruthy();
		});
	});

	describe('Profile content', () => {
		it('should render content col',  () => {
			const contentElem = fixture.debugElement.query(By.css('.mp-profile__col--content'));

			expect(contentElem).toBeTruthy();
		});

		it('should render projected content inside content col',  () => {
			const contentElem = fixture.debugElement.query(By.css('.mp-profile__col--content'));

			expect(contentElem.nativeElement.innerText).toBe('Page Content');
		});
	});
});
