import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { UrlHtmlRendererComponent } from './url-html-renderer.component';

describe('UrlHtmlRendererComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(UrlHtmlRendererComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-html-renderer> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const htmlRendererComponent = host.queryCss('spy-html-renderer');

        expect(htmlRendererComponent).toBeTruthy();
    });

    it('should bound `@Input(url)` to the `urlHtml` input of <spy-html-renderer> component', async () => {
        const mockUrl = './mock-url';
        const host = await createComponentWrapper(createComponent, { url: mockUrl });
        const htmlRendererComponent = host.queryCss('spy-html-renderer');

        expect(htmlRendererComponent.properties.urlHtml).toBe(mockUrl);
    });

    it('should bound `@Input(method)` to the `urlMethod` input of <spy-html-renderer> component', async () => {
        const mockMethod = 'GET';
        const host = await createComponentWrapper(createComponent, { method: mockMethod });
        const htmlRendererComponent = host.queryCss('spy-html-renderer');

        expect(htmlRendererComponent.properties.urlMethod).toBe(mockMethod);
    });
});
