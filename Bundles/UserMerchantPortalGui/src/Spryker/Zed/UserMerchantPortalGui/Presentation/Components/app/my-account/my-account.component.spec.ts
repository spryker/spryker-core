import { Component, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { MyAccountModule } from './my-account.module';

describe('MyAccountComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    @Component({
        selector: 'test',
        template: `
            <mp-my-account>
                <h1 title class="test-title">Title</h1>
                <spy-button action type="submit" class="test-action">
                    Button
                </spy-button>

                <div class="test-content">
                    Page Content
                </div>
            </mp-my-account>
        `,
    })
    class TestComponent {}

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            imports: [MyAccountModule],
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

    describe('My account header', () => {
        it('should render page header', () => {
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

        expect(contentElem.nativeElement.textContent).toMatch('Page Content');
    });
});
