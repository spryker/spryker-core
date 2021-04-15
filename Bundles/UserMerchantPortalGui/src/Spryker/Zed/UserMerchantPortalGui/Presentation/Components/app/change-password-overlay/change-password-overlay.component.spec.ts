import { Component, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { ChangePasswordOverlayModule } from './change-password-overlay.module';

describe('ChangePasswordOverlayComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <mp-change-password-overlay>
                <h3 title class="test-title">Title</h3>
                <spy-button action type="submit" class="test-action">
                    Button
                </spy-button>

                <div class="test-content">
                    Content
                </div>
            </mp-change-password-overlay>
        `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [ChangePasswordOverlayModule],
            declarations: [TestComponent],
            schemas: [CUSTOM_ELEMENTS_SCHEMA],
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

    describe('Change password overlay header', () => {
        it('should render component header', () => {
            const headerElem = fixture.debugElement.query(By.css('spy-headline'));

            expect(headerElem).toBeTruthy();
        });

        it('should render projected title inside header', () => {
            const titleElem = fixture.debugElement.query(By.css('.test-title'));

            expect(titleElem).toBeTruthy();
        });

        it('should render projected action inside header', () => {
            const actionElem = fixture.debugElement.query(By.css('.test-action'));

            expect(actionElem).toBeTruthy();
        });
    });

    it('should render projected content inside component', () => {
        const contentElem = fixture.debugElement.query(By.css('.test-content'));

        expect(contentElem.nativeElement.textContent).toMatch('Content');
    });
});
