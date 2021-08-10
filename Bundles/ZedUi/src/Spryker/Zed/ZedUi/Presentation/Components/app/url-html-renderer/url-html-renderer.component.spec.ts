import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { UrlHtmlRendererComponent } from './url-html-renderer.component';

const mockUrl = './mock-url';
const mockMethod = 'GET';

describe('UrlHtmlRendererComponent', () => {
    let component: UrlHtmlRendererComponent;
    let fixture: ComponentFixture<UrlHtmlRendererComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [UrlHtmlRendererComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(UrlHtmlRendererComponent);
        component = fixture.componentInstance;
    });

    it('should render <spy-user-menu> component', () => {
        const htmlRendererComponent = fixture.debugElement.query(By.css('spy-html-renderer'));

        expect(htmlRendererComponent).toBeTruthy();
    });

    it('should bound `@Input(url)` to the `urlHtml` input of <spy-html-renderer> component', () => {
        const htmlRendererComponent = fixture.debugElement.query(By.css('spy-html-renderer'));

        component.url = mockUrl;
        fixture.detectChanges();

        expect(htmlRendererComponent.properties.urlHtml).toBe(mockUrl);
    });

    it('should bound `@Input(method)` to the `urlMethod` input of <spy-html-renderer> component', () => {
        const htmlRendererComponent = fixture.debugElement.query(By.css('spy-html-renderer'));

        component.method = mockMethod;
        fixture.detectChanges();

        expect(htmlRendererComponent.properties.urlMethod).toBe(mockMethod);
    });
});
