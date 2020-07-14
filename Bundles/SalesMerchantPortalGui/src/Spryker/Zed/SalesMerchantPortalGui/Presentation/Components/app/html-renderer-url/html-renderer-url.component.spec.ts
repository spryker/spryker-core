import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { NO_ERRORS_SCHEMA } from '@angular/core';

import { HtmlRendererUrlComponent } from './html-renderer-url.component';

describe('HtmlRendererUrlComponent', () => {
    let component: HtmlRendererUrlComponent;
    let fixture: ComponentFixture<HtmlRendererUrlComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [HtmlRendererUrlComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(HtmlRendererUrlComponent);
        component = fixture.componentInstance;
    });

    it('should render `spy-html-renderer` component', () => {
        const htmlRendererElem = fixture.debugElement.query(By.css('spy-html-renderer'));

        expect(htmlRendererElem).toBeTruthy();
    });

    it('should bind @Input(url) to the `urlHtml` input of the `spy-html-renderer` component', () => {
        const mockUrl = 'url';

        component.url = mockUrl;
        fixture.detectChanges();

        const htmlRendererElem = fixture.debugElement.query(By.css('spy-html-renderer'));

        expect(htmlRendererElem.properties.urlHtml).toBe(mockUrl);
    });
});
