import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ManageOrderCollapsibleTotalsComponent } from './manage-order-collapsible-totals.component';

describe('ManageOrderCollapsibleTotalsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ManageOrderCollapsibleTotalsComponent, {
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

    it('should render <spy-collapsible> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const collapsibleComponent = host.queryCss('spy-collapsible');

        expect(collapsibleComponent).toBeTruthy();
    });

    it('should bound `@Input(url)` to the `urlHtml` input of <spy-html-renderer> component only when `activeChange` event handled on the <spy-collapsible> component', async () => {
        const mockUrl = 'url';
        const host = await createComponentWrapper(createComponent, { url: mockUrl });
        const collapsibleComponent = host.queryCss('spy-collapsible');
        const htmlRendererComponent = host.queryCss('spy-html-renderer');

        expect(htmlRendererComponent.properties.urlHtml).toBe(undefined);

        collapsibleComponent.triggerEventHandler('activeChange', true);
        host.detectChanges();

        expect(htmlRendererComponent.properties.urlHtml).toBe(mockUrl);
    });
});
