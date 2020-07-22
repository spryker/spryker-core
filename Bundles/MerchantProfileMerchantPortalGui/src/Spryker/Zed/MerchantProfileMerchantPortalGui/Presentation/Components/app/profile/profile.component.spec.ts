import { Component, CUSTOM_ELEMENTS_SCHEMA } from "@angular/core";
import { async, ComponentFixture, TestBed } from "@angular/core/testing";
import { By } from "@angular/platform-browser";

import { ProfileModule } from "./profile.module";

describe("ProfileComponent", () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: "test",
        template: `
            <mp-profile>
                <h1 title class="test-title">Title</h1>

                Page Content

                <spy-button action type="submit" class="test-action">
                    Button
                </spy-button>
            </mp-profile>
        `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ProfileModule],
            declarations: [TestComponent],
            schemas: [CUSTOM_ELEMENTS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(TestComponent);
        component = fixture.componentInstance;

        fixture.detectChanges();
    });

    it("should create component", () => {
        expect(component).toBeTruthy();
    });

	describe('Profile header', () => {
		it('should render page header',  () => {
			const headerElem = fixture.debugElement.query(By.css('spy-headline'));

            expect(headerElem).toBeTruthy();
        });

		it('should render projected title inside header',  () => {
			const titleElem = fixture.debugElement.query(By.css('spy-headline .test-title'));

            expect(titleElem).toBeTruthy();
        });

		it('should render projected action inside header',  () => {
			const actionElem = fixture.debugElement.query(By.css('spy-headline .test-action'));

            expect(actionElem).toBeTruthy();
        });
    });

    describe("Profile content", () => {
        it("should render content col", () => {
            const contentElem = fixture.debugElement.query(
                By.css(".mp-profile__col--content")
            );

            expect(contentElem).toBeTruthy();
        });

        it("should render projected content inside content col", () => {
            const contentElem = fixture.debugElement.query(
                By.css(".mp-profile__col--content")
            );

            expect(contentElem.nativeElement.textContent).toMatch("Page Content");
        });
    });
});
